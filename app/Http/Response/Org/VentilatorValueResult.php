<?php

namespace App\Http\Response\Org;

use App\Http\Response\SuccessJsonResult;

class VentilatorValueResult extends SuccessJsonResult
{
    public $id;

    public $patient_code;

    public $gs1_code;

    public $registered_user_name;

    public $registered_at;

    public $updated_at;

    public $status;

    public $fixed_flg;

    public $confirmed_flg;

    public $height;

    public $weight;

    public $gender;

    public $airway_pressure;

    public $air_flow;

    public $o2_flow;

    public $fio2;

    public $rr;

    public $estimated_vt;

    public $estimated_mv;

    public $estimated_peep;

    public $status_use;

    public $status_use_other;

    public $spo2;

    public $etco2;

    public $pao2;

    public $paco2;
}