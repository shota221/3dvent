<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms as Form;
use App\Services as Service;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\LanguageService;
    }

    public function asyncSwitchLanguage(string $language_code)
    {
        $form = new Form\LanguageCodeSelectForm(compact('language_code'));

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getLanguageCodeSetting($form);
    }
}
