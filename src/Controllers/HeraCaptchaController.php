<?php

namespace Hera\Captcha\Controllers;

use Hera\Captcha\HeraCaptcha;

class HeraCaptchaController
{
    public function getcaptcha()
    {
        $HeraCaptcha = new HeraCaptcha();
        return response()->json(
            $HeraCaptcha->generate()
        );
    }
}
