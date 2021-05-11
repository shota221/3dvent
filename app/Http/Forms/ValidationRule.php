<?php

namespace App\Http\Forms;

class ValidationRule
{

    const
        VALUE_INTEGER           = 'integer|max:999999999', // intval最大=9223372036854775807 js int最大Number.MAX_SAFE_INTEGER=9007199254740991

        VALUE_POSITIVE_INTEGER  = 'integer|min:0|max:999999999',

        VALUE_POSITIVE          = 'numeric|min:0',

        VALUE_NAME              = 'string|max:100',

        VALUE_STRING            = 'string|max:250',

        VALUE_TEXT              = 'string|max:500',

        PROBABILITY             = 'numeric|min:0|max:100',

        PASSWORD          = 'string|min:8|max:32',

        EMAIL               = 'string|max:120|email',

        VALUE_BOOLEAN   = 'boolean',

        FLG_INTEGER = 'integer|min:0|max:1';

    /**
     * ある文字列が含まれない正規表現を返す
     *
     * @param [type] $string
     * @return void
     */
    public static function stringExclude(string $string)
    {
        return 'regex:#^(?!.*' . $string . ').*$#';
    }
}
