<?php
namespace App\Services\Api\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ApiServiceProvider class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class ApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(\App\Services\Api\Console\RegisterApiCommand::class);
    }
}
