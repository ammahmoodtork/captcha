<?php

namespace Hera\Captcha;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class HeraCaptcha
{

    /**
     * language characters can be fa for Persian, ar for Arabic anddefault is en for English
     */
    private $numbersLangs = 'en';

    private $length = 5;
    private $width = 120;
    private $height = 50;
    private $bgColor;
    private $fontColors = ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'];
    private $expire = 60;
    private function setConfig($config)
    {
        $this->numbersLangs = isset($config['numbersLangs']) ? $config['numbersLangs'] : "eb";
        $this->length = isset($config['length']) ? $config['length'] : 5;
        $this->width = isset($config['width']) ? $config['width'] : 120;
        $this->height = isset($config['height']) ? $config['height'] : 50;
        $this->bgColor = isset($config['bgColor']) ? sscanf($config['bgColor'], "#%02x%02x%02x") : sscanf("#ecf2f4", "#%02x%02x%02x");
        $this->fontColors = isset($config['fontColors']) ? $config['fontColors'] : ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'];
        $this->expire = isset($config['expire']) ? $config['expire'] : 60;
    }

    public function generate($conf = 'default')
    {
        $config = config('captcha.' . $conf);
        $this->setConfig($config);
        try {
            $photoAddress = $this->getBackgroudImage();
            $img = imagecreatefrompng($photoAddress);
            imagecopyresized($img, $img, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);
        } catch (\Throwable $th) {
            $img = imagecreate($this->width, $this->height);
            $textbgcolor = imagecolorallocate($img, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
        }
        $char = $this->getText();
        $txt = '';
        for ($i = 0; $i < count($char); $i++) {
            $color = $this->getfontColors();
            $textcolor = imagecolorallocate($img, $color[0], $color[1], $color[2]);
            imagettftext($img, 20, rand(-40, 40), ($i * ($this->width / $this->length)) + 5, $this->height * 0.8, $textcolor, (__DIR__ . "/assets/fonts/IRANSansWeb.ttf"), $this->getchar($char[$i]));
            $txt .= $char[$i];
        }
        ob_start();
        imagepng($img);
        $key = Hash::make($txt);
        $this->setCacheKey($key);
        return [
            "img" => "data:image/png;base64," . base64_encode(ob_get_clean()),
            "key" => $key
        ];
    }

    private function setCacheKey($key)
    {
        Cache::add('captchaCache_' . $key, $key, $this->expire);
    }
    private function getCachedKey($key)
    {
        return Cache::get('captchaCache_' . $key, null);
    }

    private function getBackgroudImage()
    {
        $name = str_pad(rand(1, 12), 2, "0", STR_PAD_LEFT);
        return (__DIR__ . "/assets/backgrounds/{$name}.png");
    }

    private function getText($conf = 'default')
    {
        $characters = config('captcha.characters');
        $textArray = [];
        for ($i = 0; $i < $this->length; $i++) {
            $textArray[] = $characters[rand(0, count($characters) - 1)];
        }
        return $textArray;
    }

    private function getchar($char)
    {
        // return $char;
        if ($this->numbersLangs == 'en')
            return $char;
        $numbers = [
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
            '0' => '۰',
        ];
        return empty($numbers[$char]) ? $char : $numbers[$char];
    }

    private function getfontColors($conf = 'default')
    {
        // $colors = config('captcha.fontColors');
        $colors = $this->fontColors;
        $hex = $colors[rand(0, count($colors) - 1)];
        $color = sscanf($hex, "#%02x%02x%02x");
        return $color;
    }

    public function checkCaptcha($captcha, $key, $config)
    {
        if (!$this->getCachedKey($key)) {
            return false;
        }
        return Hash::check($captcha, $key);
    }
}
