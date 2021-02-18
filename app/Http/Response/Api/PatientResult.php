<?php

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class PatientResult extends SuccessJsonResult
{
    public $patient_id;

    public $nickname;

    public $height;

    public $weight;

    public $other_attrs;
}