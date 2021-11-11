<?php

namespace App\Services;

use App\Exceptions;
use App\Http\Forms as Form;
use App\Http\Response as Response;
use App\Services\Support\Converter;

class LanguageService
{
    public function getLanguageCodeSetting(Form\LanguageCodeSelectForm $form)
    {   
        return Converter\CookieConverter::convertToLanguageCookieSetting(
            $form->language_code,
            config('cookie.language_key'), 
            config('cookie.domain'), 
            config('cookie.path'), 
            config('cookie.max_age'));
    }
}
