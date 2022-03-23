<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientUpdateForm extends BaseForm
{
    public $id;

    public $patient_code;

    public $weight;
    
    protected function validationRule()
    {
        return [
            'id'           => 'required|'.Rule::VALUE_INTEGER,

            'patient_code' => 'nullable|'.Rule::VALUE_STRING,

            'weight'       => 'required|'.Rule::VALUE_POSITIVE.'|max:999'
        ];  
    }

    protected function bind($input)
    {
        $this->id           = $input['id'];

        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;

        $this->weight       = strval(round($input['weight'],1));
    }
}