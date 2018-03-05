<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->configure('api');
$app->configure('doctrine');
$app->configure('queue');
$app->configure('cache');
$app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->bind(
    App\Interfaces\MicroserviceProviderInterface::class,
    App\Services\Api\Providers\RedisMicroserviceProvider::class
);

$app->singleton(
    App\Interfaces\MicroserviceRegistryInterface::class,
    App\Services\Api\MicroserviceRegistry::class
);

$app->singleton(
    App\Services\Api\Client::class,
    App\Services\Api\Client::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->alias(App\Services\Api\Client::class, 'api');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
]);

$app->routeMiddleware([
    'api'         => App\Http\Middleware\ApiMiddleware::class,
    'contentType' => App\Http\Middleware\ContentTypeMiddleware::class,
    'response'    => App\Http\Middleware\ResponseFormatterMiddleware::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(LaravelDoctrine\ORM\DoctrineServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(\App\Services\Api\Providers\ApiServiceProvider::class);
/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix'    => 'api'
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

class_alias('LaravelDoctrine\ORM\Facades\EntityManager', 'EntityManager');
class_alias('LaravelDoctrine\ORM\Facades\Registry', 'Registry');
class_alias('LaravelDoctrine\ORM\Facades\Doctrine', 'Doctrine');

return $app;