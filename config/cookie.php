<?php

return [    
    /*
    |--------------------------------------------------------------------------
    | 言語切替用
    |--------------------------------------------------------------------------
    |
    */

    'language_key' => 'applocale',
    'admin_domain' => env('COOKIE_ADMIN_DOMAIN'),
    'org_domain'   => env('COOKIE_ORG_DOMAIN'),
    'path'         => env('COOKIE_PATH'),
    'max_age'      => env('COOKIE_MAX_AGE'),
];
