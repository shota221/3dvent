<?php

namespace App\Http\Response\Admin;

use App\Http\Response\SuccessJsonResult;

class PatientValueEditData extends SuccessJsonResult 
{
    public $id;

    public $patient_code;
    
    public $organization_name;
    
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
    
    public $adverse_event_flg;
    
    public $adverse_event_contents;

}