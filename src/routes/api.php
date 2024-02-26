<?php

use Hera\HeraCaptcha\Controllers\HeraCaptchaController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('captcha', [HeraCaptchaController::class , 'getcaptcha']);
});
