<?php 

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class CookieSettingResult extends SuccessJsonResult
{
    public $cookie_key;
    public $language_code;
    public $domain;
    public $path;
    public $max_age;
}