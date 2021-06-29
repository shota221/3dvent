<?php

namespace App\Services\Support;

class Statistic
{
    public $mean;
    public $sumsq;
    public $sVariance;
    public $ssd;

    function __construct($inputs)
    {
        $this->mean = $this->mean($inputs);
        $this->sumsq = $this->sumsq($inputs);
        $this->sVariance = $this->sVariance($inputs);
        $this->ssd = $this->ssd($inputs);
    }
    /**
     * 平均
     */
    private function mean($inputs){
        return array_sum($inputs)/count($inputs);
    }

    /**
     * 平均との差の二乗和
     */
    private function sumsq($inputs){
        $sumsq = 0;
        foreach($inputs as $input){
            $sumsq += pow(abs($input - $this->mean), 2);
        }
        return $sumsq;
    }

    /**
     * 標本分散
     */
    private function sVariance($inputs){
        return $this->sumsq/count($inputs);
    }

    /**
     * 標本標準偏差
     */
    private function ssd($inputs){
        return sqrt($this->sVariance);
    }
    
    /**
     * 偏差値
     */
    public function standardScore($value){
        return ($value-$this->mean)/$this->ssd * 10 + 50;
    }
}