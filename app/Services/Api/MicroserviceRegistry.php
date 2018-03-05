<?php
namespace App\Services\Api;

use App\Exceptions\ServiceNotExists;
use App\Interfaces\MicroserviceRegistryInterface;
use App\Interfaces\MicroserviceProviderInterface;

/**
 * MicroserviceRegistry class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class MicroserviceRegistry implements MicroserviceRegistryInterface
{
    /**
     * @var array
     */
    private $microservices = [];

    /**
     * @var MicroserviceProviderInterface
     */
    private $provider;


    /**
     * MicroserviceRegistry constructor.
     *
     * @param MicroserviceProviderInterface $provider
     */
    public function __construct(MicroserviceProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    /**
     * @param bool $refresh
     * @return array
     */
    public function getMicroservices($refresh = false) : array
    {
        if (!$refresh) {
            return $this->microservices;
        }

        $this->microservices = $this->provider->resolve();

        return $this->microservices;
    }


    /**
     * @param string $name
     * @param bool $refresh
     * @return array
     * @throws ServiceNotExists
     */
    public function getMicroserviceByName(string $name, bool $refresh = true) : array
    {
        if (!isset($this->microservices[ $name ])) {

            if ($refresh) {
                $this->getMicroservices(true);
                return $this->getMicroserviceByName($name, false);
            }

            throw new ServiceNotExists();
        }

        return $this->microservices[ $name ];
    }


    /**
     * @param string $name
     * @param array $info
     */
    public function registerMicroservice(string $name, array $info)
    {
        $this->microservices[ $name ] = $info;
        $this->provider->register($name, $info);
    }
}
