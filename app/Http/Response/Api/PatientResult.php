<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class PatientResult extends SuccessJsonResult
{
    public $patient_id;

    public $nickname;

    public $gender;

    public $weight;

    public $ideal_weight;

    public $predicted_vt;

    public $other_attrs;
}