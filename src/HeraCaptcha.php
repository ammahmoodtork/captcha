<?php

namespace Hera\Captcha;

use Illuminate\Support\Facades\Hash;

class HeraCaptcha
{
    public function generate($conf = 'default')
    {
        $config = config('captcha.' . $conf);

        // $img = imagecreate(120, 36);
        try {
            $photoAddress = (__DIR__ . "/assets/backgrounds/01.png");
            $img = imagecreatefrompng($photoAddress);
        } catch (\Throwable $th) {
            $img = imagecreate(120, 50);
            $textbgcolor = imagecolorallocate($img, 255, 255, 255);
        }

        $textbgcolor = imagecolorallocate($img, 255, 255, 255);
        $char = $this->getText();
        $txt = '';
        for ($i = 0; $i < count($char); $i++) {
            $color = $this->getfontColors();
            $textcolor = imagecolorallocate($img, $color[0], $color[1], $color[2]);
            imagettftext($img, 20, rand(-40, 40), ($i * (120 / $config['length'])) + 5, 28, $textcolor, (__DIR__ . "/assets/fonts/IRANSansWeb.ttf"), $char[$i]);
            $txt .= $char[$i];
        }
        // imagettftext($img, 20, 0, 5, 28, $textcolor, (__DIR__ . "/assets/IRANSansWeb.ttf"), $txt);
        ob_start();
        imagepng($img);
        return [
            "img" => "data:image/png;base64," . base64_encode(ob_get_clean()),
            "key" => Hash::make($txt)
        ];
    }

    private function getText($conf = 'default')
    {
        $characters = config('captcha.characters');
        $config = config('captcha.' . $conf);
        $textArray = [];
        for ($i = 0; $i < $config['length']; $i++) {
            $textArray[] = $characters[rand(0, count($characters) - 1)];
        }
        return $textArray;
    }

    private function getfontColors($conf = 'default')
    {
        // $colors = config('captcha.fontColors');
        $colors = config('captcha.' . $conf)['fontColors'];
        $hex = $colors[rand(0, count($colors) - 1)];
        $color = sscanf($hex, "#%02x%02x%02x");
        return $color;
    }
}
