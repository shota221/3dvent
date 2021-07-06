<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientCreateForm extends BaseForm
{
    public $patient_code;

    public $height;

    public $gender;

    public $ventilator_id;
    
    protected function validationRule()
    {
        return [
            'patient_code' => 'nullable|'.Rule::VALUE_STRING,
        
            'height' => 'required|'.Rule::VALUE_POSITIVE.'|max:999',

            'weight' => 'nullable|'.Rule::VALUE_POSITIVE.'|max:999',
        
            'gender' => 'required|integer|min:1|max:2',

            'ventilator_id'=> 'required|'.Rule::VALUE_INTEGER
        ];  
    }

    protected function bind($input)
    {
        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;

        $this->height = strval(round($input['height'],1));

        $this->weight = isset($input['weight']) ? strval(round($input['weight'],1)) : '';

        $this->gender = intval($input['gender']);

        $this->ventilator_id = intval($input['ventilator_id']);
    }
}