<?php
use App\Http\Forms\ValidationRule as Rule;
use App\Http\Auth;

return [
    'filename' => 'user_format.csv',

    'header' => [
        'name'               => 'ユーザー名',
        'email'              => 'メールアドレス(医師（研究代表者)の場合は必須',
        'org_authority_type' => '権限（医師（研究代表者）:1, 医師（その他）:2,CRC:3,看護師:4,臨床工学士:5）',
        'password'           => 'パスワード',
    ],

    'validation_rule' => [
        'name'               => 'required|' . Rule::VALUE_NAME,
        'email'              => 'nullable|required_if:org_authority_type,' . Auth\OrgUserGate::AUTHORITIES['principal_investigator']['type'] . '|' . Rule::EMAIL,
        'org_authority_type' => 'required|' . Rule::ORG_AUTHORITY_TYPE,
        'password'           => 'required|' . Rule::PASSWORD,
    ],

    'dupulicate_confirmation_targets' => [
        'name',
    ],

    'example' => [
        'suzuki',
        'suzuki@sample.com',
        '1',
        'password',
    ],
];