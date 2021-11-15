<?php

namespace App\Http\Forms\Manual;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class LanguageCodeForm extends BaseForm
{
    public $language_code;

    protected function validationRule()
    {
        return [
            'language_code' => 'required|' . Rule::VALUE_STRING,
        ];
    }

    protected function bind($input)
    {
        $this->language_code = strval($input['language_code']);
    }

    // ユーザーがスマホ端末に設定している言語コードがconfig('languages')に定義されていない
    // 言語コードだった場合にはアプリのデフォルト言語をセット
    protected function validateAfterBinding()
    {
        $language_code_keys = array_keys(config('languages'));

        $exists = in_array($this->language_code, $language_code_keys);

        if (! $exists) {
            $this->language_code = config('app.fallback_locale');
        }
    }
}