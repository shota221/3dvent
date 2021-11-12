<?php

namespace App\Services;

use App\Exceptions;
use App\Http\Forms as Form;
use App\Http\Response as Response;
use App\Services\Support\Converter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class LanguageService
{
    public function getLanguageCodeSetting(Form\LanguageCodeSelectForm $form)
    {   
        return Converter\CookieConverter::convertToLanguageCookieSetting(
            $form->language_code,
            config('cookie.language_key'), 
            config('cookie.' . env('APP_HTTP_ROUTE_TYPE') . '_domain'), 
            config('cookie.path'), 
            config('cookie.max_age'));
    }
}
