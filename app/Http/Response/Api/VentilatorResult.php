<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class VentilatorResult extends SuccessJsonResult
{
    public $ventilator_id;

    public $is_registered;

    public $patient_id;

    public $organization_name;

    public $serial_number;
    
    public $start_using_at;
}