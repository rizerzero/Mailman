<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Option;


class OptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

       $this->app->singleton('App\Option', function($app) {
            return new Option;
        });
    }
}
