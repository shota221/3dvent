<?php

namespace App\Services;

use App\Exceptions;
use App\Http\Forms as Form;
use App\Http\Response as Response;
use App\Services\Support\Converter;
use Illuminate\Support\Facades\Session;

class LanguageService
{
    public function setLanguageCode(Form\LanguageCodeSelectForm $form)
    {   
        $language_key = config('session.language_key');

        Session::put($language_key, $form->language_code);

        return new Response\SuccessJsonResult();
    }
}
