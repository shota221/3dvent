<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class MeasurementValueResult extends SuccessJsonResult
{   
    public $id;

    public $status_use;

    public $status_use_other;

    public $spo2;

    public $etco2;

    public $pao2;

    public $paco2;
}