<?php
namespace App\Interfaces;

/**
 * MicroserviceRegistryInterface interface
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
interface MicroserviceRegistryInterface
{

    /**
     * MicroserviceRegistryInterface constructor.
     *
     * @param MicroserviceProviderInterface $resolver
     */
    public function __construct(MicroserviceProviderInterface $resolver);


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
     * @param bool $refresh Refresh list of microservices.
     * @return array
     */
    public function getMicroservices($refresh = false) : array;


    /**
     * Returns information about microservice:
     * <code>
     * [
     *       'url'   => 'http://localhost/api/
     *       actions => [
     *           'action_name' => 'action_route',
     *           ...
     * ]
     * </code>
     * @param string $name Microservice name
     * @param bool $refresh Refresh list of microservices if the service with the given name not found.
     * @return array
     */
    public function getMicroserviceByName(string $name, bool $refresh = true) : array;


    /**
     * Registers a new microservice.
     * @param string $name Microservice name.
     * @param array $info
     * <code>
     *  [
     *       'url'   => 'http://localhost/api/
     *       actions => [
     *           'action_name' => 'action_route',
     *           ...
     * ]
     * </code>
     */
    public function registerMicroservice(string $name, array $info);
}