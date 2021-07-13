<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class IeResult extends SuccessJsonResult
{
    public $i_avg;

    public $e_avg;

    public $rr;

    public $ie_ratio;
}