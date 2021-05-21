<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class VentilatorValueResult extends SuccessJsonResult
{   
    public $id;

    public $ventilator_id;

    public $height;

    public $weight;

    public $gender;

    public $ideal_weight;
    
    public $airway_pressure;

    public $total_flow;
    
    public $air_flow;
    
    public $o2_flow;
    
    public $rr;
        
    public $estimated_vt;
    
    public $estimated_mv;
    
    public $estimated_peep;
    
    public $fio2;   

    public $fixed_flg;

    public $registered_at;

    public $registered_user_name;

    public $confirmed_user_name;

    public $status_use;

    public $status_use_other;

    public $spo2;

    public $etco2;

    public $pao2;

    public $paco2;

    public $revised_at;
}