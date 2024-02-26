<?php

use Hera\Captcha\Controllers\HeraCaptchaController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('captcha', [HeraCaptchaController::class , 'getcaptcha']);
});
