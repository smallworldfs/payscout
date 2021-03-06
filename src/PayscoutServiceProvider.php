<?php

namespace Smallworldfs\Payscout;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class PayscoutServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // modify this if you want disable tutorial routes
        $this->setupRoutes($this->app->router);
        
        
        //php artisan vendor:publish --provider="Smallworldfs\Payscout\PayscoutServiceProvider"
        $this->publishes([
                __DIR__.'/config/payscout.php' => config_path('payscout.php'),
        ]);
        
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/payscout.php', 'payscout'
        );
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Smallworldfs\Payscout\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPayscout();
        
        //use this if your package has a config file
        config([
                'config/payscout.php',
        ]);
    }

    private function registerPayscout()
    {
        $this->app->bind('payscout',function($app){
            return new Payscout($app);
        });
    }
}