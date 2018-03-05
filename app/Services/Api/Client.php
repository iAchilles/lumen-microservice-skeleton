<?php
namespace App\Services\Api;

use App\Exceptions\MethodNotExists;
use App\Interfaces\MicroserviceRegistryInterface;

/**
 * Client class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class Client
{
    /**
     * @var MicroserviceRegistryInterface
     */
    private $registry;


    /**
     * Client constructor.
     *
     * @param MicroserviceRegistryInterface $registry
     */
    public function __construct(MicroserviceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }


    /**
     * @param string $name
     * @param $arguments
     * @throws ServiceNotExists
     * @return array
     * @throws MethodNotExists
     */
    public function __call($name, $arguments)
    {
        $service = $this->registry->getMicroserviceByName($name);
        $actions = $service[ 'actions' ];

        if (!isset($actions[ reset($arguments) ])) {
            throw new MethodNotExists();
        }

        $url = $service[ 'url' ] . $actions[ reset($arguments) ];
        $params = isset($actions[ 1 ]) ? $actions[ 1 ] : [];

        return $this->call($url, $params);
    }


    /**
     * @param string $url
     * @param $params
     * @return array|mixed
     */
    protected function call(string $url, $params)
    {
        $query = is_array($params) ? $params : [ 'id' => $params ];
        $query = json_encode($query);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHttpHeaders());
        $result        = curl_exec($curl);
        $errorMessage  = curl_error($curl);
        $errorCode     = curl_errno($curl);
        $code          = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if ($result == false) {
            return [ 'status' => 'error', 'error' => [ 'code' => $errorCode, 'message' => $errorMessage ] ];
        }

        $response = json_decode($result, true);
        if (is_null($response)) {
            return [ 'status' => 'error', 'error' => [ 'code' => $code, 'message' => 'HTTP error' ]];
        }

        return $response;
    }


    /**
     * @return array
     */
    protected function getHttpHeaders()
    {
        return [
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('api.secret')
        ];
    }
}
