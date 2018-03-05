<?php
namespace App\Services\Api\Providers;

use App\Interfaces\MicroserviceProviderInterface;
use Illuminate\Support\Facades\Redis;

/**
 * RedisMicroserviceProvider class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class RedisMicroserviceProvider implements MicroserviceProviderInterface
{

    /**
     * @return array
     */
    public function resolve() : array
    {
        $services = Redis::command('HGETALL', [ 'microservices' ]);
        if (!is_array($services) || empty($services)) {
            return [];
        }

        $list = [];

        foreach ($services as $key => $value) {
            $info = json_decode($value, true);
            $list[ $key ] = $info;
        }

        return $list;
    }


    /**
     * @param string $name
     * @param array $info
     */
    public function register(string $name, array $info)
    {
        Redis::command('HSET', [ 'microservices', $name, json_encode($info) ]);
    }
}
