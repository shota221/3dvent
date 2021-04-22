<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class VentilatorValueResult extends SuccessJsonResult
{   
    public $id;

    public $ventilator_id;
    
    public $airway_pressure;
    
    public $air_flow;
    
    public $o2_flow;
    
    public $rr;
    
    public $spo2;
    
    public $estimated_vt;
    
    public $estimated_mv;
    
    public $estimated_peep;
    
    public $fio2;   

    public $fixed_flg;

    public $observed_at;

    public $observed_user_name;
}