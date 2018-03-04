<?php
namespace App\Services\Api\Console;

use App\Interfaces\MicroserviceRegistryInterface;
use Illuminate\Console\Command;

/**
 * RegisterApiCommand class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class RegisterApiCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'api:register';

    /**
     * @var string
     */
    protected $description = 'Registers routes of the microservice';


    public function handle()
    {
        /**
         * @var MicroserviceRegistryInterface $provider
         */
        $provider = app(MicroserviceRegistryInterface::class);
        $host     = $this->getHost();
        $name     = $this->getNodeName();
        $actions  = $this->getActions();

        $info = [ 'url' => $host, 'actions' => $actions ];

        if (empty($actions)) {
            $this->info('Microservice does not contain any available routes to register');
            return;
        }

        $headers = [ 'Action', 'Route' ];
        $routes  = [];

        foreach ($actions as $key => $value) {
            array_push($routes, [ 'action' => $key, 'route' => $value ]);
        }

        $provider->registerMicroservice($name, $info);
        $this->info('Microservice host: ' . $host);
        $this->info('Microservice name:' . $name);
        $this->info('Registered routes:');
        $this->table($headers, $routes);
    }


    /**
     * @return string
     */
    private function getHost()
    {
        return config('api.protocol') . '://' . config('api.host');
    }


    /**
     * @return string
     */
    private function getNodeName()
    {
        return config('api.name');
    }


    /**
     * @return array
     */
    private function getActions()
    {
        $routes  = app()->router->getRoutes();
        $actions = [];

        foreach ($routes as $route) {
            $actions[ $route[ 'uri' ] ] = $route[ 'uri' ];
        }

        return $actions;
    }
}
