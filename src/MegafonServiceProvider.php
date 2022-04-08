<?php

namespace Meshgroup\Megafon;

use Illuminate\Support\ServiceProvider;

class MegafonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MegafonApi::class, function ($app) {
            return new MegafonApi($app['config']['services.megafon']);
        });
    }
}
