<?php

namespace Humweb\Teams\Tests;

use Orchestra\Testbench\TestCase as Testbench;

class TestCase extends Testbench
{
    public function setUp()
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'testing']);
//        $this->loadMigrationsFrom([
//            '--database' => 'testing',
//            '--realpath' => realpath(__DIR__.'/migrations'),
//        ]);
        $this->artisan('migrate', ['--database' => 'testing']);

        $this->withFactories(__DIR__.'/factories');
    }


    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }


    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [

        ];
    }


    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Humweb\Teams\TeamsServiceProvider',
            'Humweb\Teams\EventServiceProvider'
        ];
    }
}