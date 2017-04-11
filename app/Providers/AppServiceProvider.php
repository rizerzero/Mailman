<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Message;
use App\Option;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {



        Message::created(function($message) {
            $message->propagatePositions();
        });

        Message::updated(function($message) {

            $message->propagatePositions();
        });

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

    }
}
