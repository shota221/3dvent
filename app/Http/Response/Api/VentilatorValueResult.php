<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class VentilatorValueResult extends SuccessJsonResult
{
    public $patient_id;
    
    public $ventilator_id;
    
    public $airway_pressure;
    
    public $flow_air;
    
    public $flow_o2;
    
    public $rr;
    
    public $spo2;
    
    public $vt;
    
    public $mv;
    
    public $peep;
    
    public $fio2;   

    public $fixed_flg;
}