<?php
namespace App\Interfaces;

/**
 * MicroserviceProviderInterface interface
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
interface MicroserviceProviderInterface
{

    /**
     * Returns list of available microservices:
     * <code>
     * [
     *    'microservice_name' => [
     *       'url'   => 'http://localhost/api/
     *       actions => [
     *           'action_name' => 'action_route',
     *           ...
     *       ]
     *     ]
     * ]
     * </code>
     * @return array
     */
    public function resolve() : array;


    /**
     * @param string $name
     * @param array $info
     */
    public function register(string $name, array $info);
}
