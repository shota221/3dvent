<?php

namespace App\Http\Forms;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class LanguageCodeSelectForm extends BaseForm
{
    public $language_code;

    protected function validationRule()
    {
        return [
            'language_code' => 'required|' . Rule::valueLanguageCode(),
        ];
    }

    protected function bind($input)
    {
        $this->language_code = strval($input['language_code']);
    }
}