<?php

namespace Humweb\Teams;

use Illuminate\Support\ServiceProvider;

class TeamsServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__.'/../resources/migrations');

        $this->publishes([__DIR__.'/../resources/config' => config_path()], 'config');
    }


    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../resources/config/teams.php', 'teams');
    }
}