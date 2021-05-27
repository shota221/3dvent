<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class ObservationCount extends SuccessJsonResult
{
    public $ventilator_observed_count;

    public $patient_observed_count;

    public $ventilator_bug_count;    
}