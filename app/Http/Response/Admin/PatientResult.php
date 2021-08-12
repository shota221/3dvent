<?php

namespace App\Http\Response\Admin;

use App\Http\Response\SuccessJsonResult;

class PatientResult extends SuccessJsonResult
{
    public $id;
    public $patient_code;
}
