<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class CalcDefaultFlowForm extends BaseForm
{
    public $patient_id;

    public $airway_pressure;
    
    protected function validationRule()
    {
        return [
            'patient_id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,
        
            'airway_pressure' => 'required|'.Rule::VALUE_POSITIVE,
        ];  
    }

    protected function bind($input)
    {
        $this->patient_id = $input['patient_id'];

        $this->airway_pressure = strval(round($input['airway_pressure'],1));
    }
}