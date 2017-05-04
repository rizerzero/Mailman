<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Message;
use App\Option;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Option::created(function($option) {
            $option->propagateChange();
        });

        Option::updated(function($option) {
            $option->propagateChange();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('dev', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
