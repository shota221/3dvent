<?php

namespace App\Http\Forms;

class ValidationRule
{

    const
        VALUE_INTEGER            = 'integer|max:999999999', // intval最大=9223372036854775807 js int最大Number.MAX_SAFE_INTEGER=9007199254740991

        VALUE_POSITIVE_INTEGER   = 'integer|min:0|max:999999999',

        VALUE_POSITIVE           = 'numeric|min:0',

        VALUE_POSITIVE_NON_ZERO  = 'numeric|min:0|not_in:0',

        VALUE_NAME               = 'string|max:100',

        VALUE_CODE               = 'string|max:100|regex:/^[A-Za-z\d_-]+$/',

        VALUE_STRING             = 'string|max:250',

        VALUE_TEXT               = 'string|max:500',

        PROBABILITY              = 'numeric|min:0|max:100',

        PASSWORD                 = 'string|min:8|max:32',

        EMAIL                    = 'string|max:100|email',

        VALUE_BOOLEAN            = 'boolean',

        FLG_INTEGER              = 'integer|min:0|max:1',

        USED_PLACE_INTEGER       = 'integer|min:1|max:9', // 使用場所 

        OUTCOME_INTEGER          = 'integer|min:1|max:4', //　使用中止時の転帰

        TREATMENT_INTEGER        = 'integer|min:1|max:6', // 使用中止後の呼吸不全治療

        GENDER_INTEGER           = 'integer|min:1|max:2',  

        STATUS_USE_INTEGER       = 'integer|min:1|max:4',

        ORG_AUTHORITY_TYPE       = 'integer|min:1|max:5',

        CSV_FILE                 = 'max:1024|file|mimes:csv,txt', //　laravelだとtxtの指定が必要

        VENTILATOR_DATA_CSV_FILE = 'max:10240|file|mimes:csv,txt', //　同上

        VALUE_ROOM_TOKEN         = 'string|max:32',

        VALUE_ROOM_NAME          = 'string|max:100';

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

    /**
     * $i以上$j以下の整数
     */
    public static function intRange(int $i, int $j)
    {
        return  'integer|min:'.$i.'|max:'.$j;
    }

    /**
     * configに設定した言語コードに含まれているかのバリデーション
     *
     * @return $string
     */
    public static function valueLanguageCode()
    {
        $language_code_keys   = array_keys(config('languages'));
        $language_code_string = implode(",", $language_code_keys);
 
        return 'string|in:' . $language_code_string;
    }
}
