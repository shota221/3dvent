<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class PatientValueResult extends SuccessJsonResult
{
    public $patient_id;

    public $has_observed;

    public $patient_code;

    public $opt_out_flg;

    public $age;

    public $vent_disease_name;

    public $other_disease_name_1;

    public $other_disease_name_2;

    public $used_place;

    public $hospital;

    public $national;

    public $discontinuation_at;

    public $outcome;

    public $treatment;

    public $registered_at;

    public $adverse_event_flg;

    public $adverse_event_contents;

}