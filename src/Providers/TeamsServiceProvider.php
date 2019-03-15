<?php

namespace Humweb\Teams\Providers;

use Humweb\Modules\ModuleBaseProvider;

class TeamsServiceProvider extends ModuleBaseProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../../resources/migrations');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'teams');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/teams'),
        ]);

        $this->publishes([__DIR__.'/../../resources/config' => config_path()], 'config');
    }


    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../resources/config/teams.php', 'teams');
    }
}