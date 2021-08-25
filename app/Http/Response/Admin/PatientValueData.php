<?php

namespace App\Http\Response\Admin;

use App\Http\Response\SuccessJsonResult;

class PatientValueData extends SuccessJsonResult 
{
    public $id;

    public $patient_code;
    
    public $organization_name;
    
    public $registered_user_name;
    
    public $registered_at;
    
    public $updated_user_name;
    
    public $updated_at;
    
    public $opt_out_flg;
    
    public $age;
    
    public $vent_disease_name;
    
    public $other_disease_name_1;
    
    public $other_disease_name_2;
    
    public $used_place;

    public $used_place_name;
    
    public $hospital;
    
    public $national;
    
    public $discontinuation_at;
    
    public $outcome;
    
    public $outcome_name;
    
    public $treatment;

    public $treatment_name;
    
    public $adverse_event_flg;
    
    public $adverse_event_contents;

}