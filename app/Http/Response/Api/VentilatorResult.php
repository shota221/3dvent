<?php

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class VentilatorResult extends SuccessJsonResult
{
    public $ventilator_id;

    public $is_registered;
}