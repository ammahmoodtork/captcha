<?php

namespace Hera\HeraCaptcha\Providers;

use Illuminate\Support\ServiceProvider;

class HeraCaptchaProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
