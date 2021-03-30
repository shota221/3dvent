<?php

return [
    'threshold' => [
        //吸気はじめのカチッ
        'in' => [
            [
                'index_min' => 9,
                'index_max' => 12,
                'freq_min' => 1550,
                'freq_max' => 2100,
                'amp' => 0.04
            ],
            [
                'index_min' => 15,
                'index_max' => 17,
                'freq_min' => 2550,
                'freq_max' => 2950,
                'amp' => 0.03
            ],
            [
                'index_min' => 27,
                'index_max' => 29,
                'freq_min' => 4650,
                'freq_max' => 5000,
                'amp' => 0.03
            ],
            [
                'index_min' => 46,
                'index_max' => 51,
                'freq_min' => 7900,
                'freq_max' => 8800,
                'amp' => 0.03
            ]
        ],
        //吸気のシュー
        'ex' => [
            [
                'index_min' => 10,
                'index_max' => 13,
                'freq_min' => 1700,
                'freq_max' => 2250,
                'amp' => 0.020
            ],
            [
                'index_min' => 36,
                'index_max' => 42,
                'freq_min' => 6200,
                'freq_max' => 7250,
                'amp' => 0.005
            ]
        ]
    ]
];
