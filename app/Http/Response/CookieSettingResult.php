<?php 

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class CookieSettingResult extends SuccessJsonResult
{
    public $language_code;
    public $cookie_key;
}