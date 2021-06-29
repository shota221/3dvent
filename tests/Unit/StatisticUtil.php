<?php

namespace Tests\Unit;

class StatisticUtil
{
    /**
     * 平均
     */
    static function mean($inputs){
        return array_sum($inputs)/count($inputs);
    }

    /**
     * 平均との差の二乗和
     */
    static function sumsq($inputs){
        $sumsq = 0;
        foreach($inputs as $input){
            $sumsq += pow(abs($input - self::mean($inputs)), 2);
        }
        return $sumsq;
    }

    /**
     * 標本分散
     */
    static function sVariance($inputs){
        return self::sumsq($inputs)/count($inputs);
    }

    /**
     * 標本標準偏差
     */
    static function ssd($inputs){
        return sqrt(self::sVariance($inputs));
    }
    
    /**
     * 偏差値
     */
    static function standardScore($inputs,$value){
        return ($value-self::mean($inputs))/self::ssd($inputs) * 10 + 50;
    }
}