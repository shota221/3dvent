<?php

return [
    'default' => [
        'rounding_precision' => 1, //小数部桁数
        'recommended_period_hour' => 48, //ventilators.start_using_atから指定時間経過後利用時にアプリ側でアラート,
        'vt_per_kg' => 6,
        'o2_concentration' => 0.21 //大気中の酸素濃度
    ],
    'parameter' => [
        /**
         * 吸気量から推定される分時換気量
         * 補正式の傾きは供給気流量と設定圧による。
         * mv = (i_avg * rr / 60) * (total_flow  - airway_pressure * a) + b
         * <備考>
         * [mv] = L/min
         * [i_avg] = s/回
         * [rr] = 回/min
         * [60] = s/min
         * [total_flow] = L/min
         * [airway_pressure] = cmH2O
         * より、
         * [a] = L/(min * cmH2O)
         */
        'estimated_mv' => [
            'a' => 0.05,
            'b' => -1.1357
        ],
        /**
         * peepは設定圧に比例
         * peep = airway_pressure * a + b
         */
        'estimated_peep' => [
            'a' => 0.2598,
            'b' => 1.5977
        ],
        /**
         * ideal_weightはheightにより算出される
         * ideal_weight = a + b * (height - c)
         */
        'ideal_weight' => [
            'male' => [
                'a' => 50.0,
                'b' => 0.91,
                'c' => 152.4
            ],
            'female' => [
                'a' => 45.0,
                'b' => 0.91,
                'c' => 152.4
            ]
        ]
    ],
    /**
     * 小数部桁数
     */
    'rounding_precision' => [
        'e_avg'          => 2,
        'estimated_mv'   => 2,
        'estimated_peep' => 1,
        'estimated_vt'   => 1,
        'fio2'           => 1,
        'i_avg'          => 2,
        'ideal_weight'   => 3,
        'ie_ratio'       => 1,
        'predicted_vt'   => 1,
        'rr'             => 1,
        'total_flow'     => 1
    ]
];
