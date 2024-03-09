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
            $photoAddress = $this->getBackgroudImage();
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
            $txt .= $this->getchar($char[$i], $conf);
        }
        // imagettftext($img, 20, 0, 5, 28, $textcolor, (__DIR__ . "/assets/IRANSansWeb.ttf"), $txt);
        ob_start();
        imagepng($img);
        return [
            "img" => "data:image/png;base64," . base64_encode(ob_get_clean()),
            "key" => Hash::make($txt)
        ];
    }

    private function getBackgroudImage()
    {
        $name = str_pad(rand(1, 12), 2, "0", STR_PAD_LEFT);
        // dd((__DIR__ . "/assets/backgrounds/{$name}.png"));
        return (__DIR__ . "/assets/backgrounds/{$name}.png");
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

    private function getchar($char, $conf)
    {
        return $char;
        dd(mb_convert_encoding(chr(ord('0') + 171), "UTF-8"), ord('۰'));
    }

    private function getfontColors($conf = 'default')
    {
        // $colors = config('captcha.fontColors');
        $colors = config('captcha.' . $conf)['fontColors'];
        $hex = $colors[rand(0, count($colors) - 1)];
        $color = sscanf($hex, "#%02x%02x%02x");
        return $color;
    }

    public function checkCaptcha($captcha, $key, $config)
    {
        return Hash::check($captcha, $key);
    }
}
