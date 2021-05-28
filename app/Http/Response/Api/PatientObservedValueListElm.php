<?php

namespace App\Http\Response\Api;

use App\Http\Response\JsonResult;

class PatientObservedValueListElm extends JsonResult
{   
    public $operation;

    public $patient_id;

    public $organization_id;
    
    public $optout;

    public $organization_name;

    public $patient_code;

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

    public $adverse_event_flg;

    public $adverse_event_contents;

    public $registered_at;
}
