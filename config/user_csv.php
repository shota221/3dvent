<?php
use App\Http\Forms\ValidationRule as Rule;
use App\Http\Auth;

return [
    'filename' => 'user_format.csv',

    // valueには多言語化用のkeyをセット
    'header' => [
        'name'               => 'user_name',
        'email'              => 'email_discription',
        'org_authority_type' => 'authority_discription',
        'password'           => 'password',
    ],

    'validation_rule' => [
        'name'               => 'required|' . Rule::VALUE_NAME,
        'email'              => 'nullable|required_if:org_authority_type,' . Auth\OrgUserGate::AUTHORITIES['principal_investigator']['type'] . '|' . Rule::EMAIL,
        'org_authority_type' => 'required|' . Rule::ORG_AUTHORITY_TYPE,
        'password'           => 'required|' . Rule::PASSWORD,
    ],

    'dupulicate_confirmation_targets' => [
        'name',
        'email',
    ],

    'example' => [
        'suzuki',
        'suzuki@sample.com',
        '1',
        'asdfasdf',
    ],
];