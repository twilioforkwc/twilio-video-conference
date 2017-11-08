<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 電場番号
        \Validator::extend('future_date', function ($attribute, $value, $parameters, $validator) {
            if ($value > date("Y-m-d H:i:s")) {
                return true;
            }
            return false;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
