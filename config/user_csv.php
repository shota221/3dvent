<?php
use App\Http\Forms\ValidationRule as Rule;
use App\Models;

return [
    'filename' => 'user_format.csv',

    'header' => [
        'name'           => 'ユーザー名',
        'email'          => 'メールアドレス(医師（研究代表者)の場合は必須',
        'authority_type' => '権限（医師（研究代表者）:6, 医師（その他）:7,CRC:8,看護師:9,臨床工学士:10）',
        'password'       => 'パスワード',
    ],

    'validation_rule' => [
        'name'           => 'required|' . Rule::VALUE_NAME,
        'email'          => 'nullable|required_if:authority_type,' . Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE . '|' . Rule::EMAIL,
        'authority_type' => 'required|' . Rule::ORG_AUTHORITY_TYPE,
        'password'       => 'required|' . Rule::PASSWORD,
    ],

    'dupulicate_confirmation_targets' => [
        'name',
    ],

    'example' => [
        'suzuki',
        'suzuki@sample.com',
        '6',
        'password',
    ],
];