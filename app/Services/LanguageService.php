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
        $language_code = $form->language_code;
        $cookie_key    = 'applocale';
        
        return Converter\CookieConverter::convertToLanguageCookieSetting(
            $form->language_code,
            $cookie_key);
    }
}
