<?php

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class IeResult extends SuccessJsonResult
{
    public $i_avg;

    public $e_avg;
}