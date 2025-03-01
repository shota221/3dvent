<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API TOKEN Definitions
    |--------------------------------------------------------------------------
    |
    | 静的トークン定義
    |
    */
   
    'api_secret_token' => env('API_TOKEN', null),

    'api_token_input_key' => env('API_TOKEN_INPUT_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | APPKEY TOKEN Definitions
    |--------------------------------------------------------------------------
    |
    | アプリキー定義
    |
    */

    'appkey_input_key' => env('APPKEY_INPUT_KEY', null),
    

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard'     => 'user_token',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'user_token' => [
            'driver'      => 'user_token', // token or session
            'provider'    => 'user',
            'input_key'   => env('USER_TOKEN_INPUT_KEY', null), // ex.) _m_t  nullable
            'storage_key' => \App\Models\User::TOKEN_COLUMN_NAME,
            'hash'        => env('USER_TOKEN_HASH', true),
        ],

        'user' => [
            'driver'   => 'session',
            'provider' => 'user'
        ],

        'admin' => [
            'driver'   => 'session',
            'provider' => 'admin'
        ],
        
        'org' => [
            'driver'   => 'session',
            'provider' => 'org'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'user' => [
            'driver' => 'eloquent_user',
            'role'   => '',
        ],

        'admin' => [
            'driver' => 'eloquent_user',
            'role'   => 'admin',
        ],
        
        'org' => [
            'driver' => 'eloquent_user',
            'role'   => 'org',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'admin' => [
            'provider' => 'admin',
            'table'    => 'user_password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'org' => [
            'provider' => 'org',
            'table'    => 'user_password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
