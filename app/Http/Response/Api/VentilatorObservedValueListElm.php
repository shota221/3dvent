<?php

namespace App\Http\Response\Api;

use App\Http\Response\JsonResult;

class VentilatorObservedValueListElm extends JsonResult
{   
    public $operation;

    public $patient_id;
    
    public $organization_id;

    public $ventilator_id;
    
    public $ventilator_value_id;

    public $serial_number;

    public $user_name;
    
    public $city;
    
    public $gender;
    
    public $height;
    
    public $weight;
    
    public $ideal_weight;
    
    public $status_use;
    
    public $status_use_other;
    
    public $vt_per_kg;
    
    public $predicted_vt;
    
    public $airway_pressure;
    
    public $air_flow;
    
    public $o2_flow;
    
    public $total_flow;
    
    public $fio2;
   
    public $inspiratory_time;
   
    public $expiratory_time;
   
    public $rr;
   
    public $estimated_vt;
   
    public $estimated_mv;
   
    public $spo2;
   
    public $etco2;
   
    public $pao2;
   
    public $paco2;
   
    public $qr_read_at;

    public $start_using_at;
   
    public $registered_at;
}
