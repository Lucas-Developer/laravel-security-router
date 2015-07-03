<?php

namespace ResultSystems\SecurityRouter\Providers;

use Illuminate\Support\ServiceProvider;
use ResultSystems\SecurityRouter\Services\SecurityRouter;

/**
     * Class SecurityRouterServiceProvider
 * @package ResultSystems\SecurityRouter
 */
class SecurityRouterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register the package.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSecurityRouter();
    }
    /**
     * Register the security router instance.
     *
     * @return void
     */
    public function registerSecurityRouter()
    {
        $app=$this->app['config'];
        $this->app->singleton('security.router', function () use ($app) {
            return new SecurityRouter($app);
        });
    }
    /**
     * The services that package provides.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'security.router'
        ];
    }
}
