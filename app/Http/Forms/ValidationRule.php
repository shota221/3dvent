<?php

namespace App\Http\Forms;

class ValidationRule
{

    const
        VALUE_INTEGER           = 'integer|max:999999999', // intval最大=9223372036854775807 js int最大Number.MAX_SAFE_INTEGER=9007199254740991

        VALUE_POSITIVE_INTEGER  = 'integer|min:0|max:999999999',

        VALUE_POSITIVE          = 'numeric|min:0',

        VALUE_POSITIVE_NON_ZERO = 'numeric|min:0|not_in:0',

        VALUE_NAME              = 'string|max:100',

        VALUE_CODE              = 'string|max:100|regex:/^[A-Za-z\d_-]+$/',

        VALUE_STRING            = 'string|max:250',

        VALUE_TEXT              = 'string|max:500',

        PROBABILITY             = 'numeric|min:0|max:100',

        PASSWORD          = 'string|min:8|max:32',

        EMAIL               = 'string|max:100|email',

        VALUE_BOOLEAN   = 'boolean',

        FLG_INTEGER = 'integer|min:0|max:1',

        USED_PLACE_INTEGER = 'integer|min:1|max:8', // 使用場所 

        OUTCOME_INTEGER = 'integer|min:1|max:4', //　使用中止時の転帰

        TREATMENT_INTEGER = 'integer|min:1|max:6'; // 使用中止後の呼吸不全治療


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
