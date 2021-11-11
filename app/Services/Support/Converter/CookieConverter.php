<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class CookieConverter
{
    public static function convertToLanguageCookieSetting(
        string $language_code, 
        string $cookie_key, 
        string $domain, 
        string $path, 
        string $max_age)
    {
        $res = new Response\CookieSettingResult;

        $res->language_code = $language_code;
        $res->cookie_key    = $cookie_key;
        $res->domain        = $domain;
        $res->path          = $path;
        $res->max_age       = $max_age;

        return $res;
    }
}