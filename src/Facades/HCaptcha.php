<?php

namespace Hera\Captcha\Facades;

use Hera\Captcha\HeraCaptcha;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Hera\Captcha\HeraCaptcha
 */
class HCaptcha extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return HeraCaptcha::class;
    }
}
