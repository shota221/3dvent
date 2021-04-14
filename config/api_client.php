<?php

return [
    'reverse_geocoding' => [
        'http' => [
            'host'              => env('API_CLIENT_REVERSE_GEOCODING_HOST'),
            'uri'               => env('API_CLIENT_REVERSE_GEOCODING_URI'),
            'request_timeout'   => env('API_CLIENT_REVERSE_GEOCODING_REQUEST_TIMEOUT',  env('API_REQUEST_TIMEOUT',   60)), // sec
            'response_timeout'  => env('API_CLIENT_REVERSE_GEOCODING_RESPONSE_TIMEOUT', env('API_RESPONSE_TIMEOUT',  60)), // sec
            'request_interval'  => env('API_CLIENT_REVERSE_GEOCODING_REQUEST_INTERVAL', env('API_REQUEST_INTERVAL',  1000.0)), // msec
        ],
    ]
];