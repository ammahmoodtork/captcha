<?php

namespace Hera\HeraCaptcha\Controllers;

use Hera\HeraCaptcha\HeraCaptcha;

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
