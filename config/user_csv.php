<?php
use App\Http\Forms\ValidationRule as Rule;

return [
    'filename' => 'user_format.csv',

    'header' => [
        'name'      => 'ユーザー名',
        'email'     => 'メールアドレス',
        'authority' => '権限',
        'password'  => 'パスワード',
    ],

    'validation_rule' => [
        'name'      => 'required|' . Rule::VALUE_NAME,
        'email'     => 'nullable|required_if:authority,1|' . Rule::EMAIL, // TODO権限回り決定後修正
        'authority' => 'required|' . Rule::VALUE_POSITIVE_INTEGER, // TODO権限回り決定後修正
        'password'  => 'required|' . Rule::PASSWORD,
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