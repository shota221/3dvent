<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientUpdateForm extends BaseForm
{
    public $id;

    public $patient_code;

    public $height;

    public $gender;

    public $weight;
    
    protected function validationRule()
    {
        return [
            'id'           => 'required|'.Rule::VALUE_INTEGER,

            'patient_code' => 'nullable|'.Rule::VALUE_STRING,
        
            'height'       => 'required|'.Rule::VALUE_POSITIVE.'|max:999',
        
            'gender'       => 'required|'.Rule::GENDER_INTEGER,

            'weight'       => 'required|'.Rule::VALUE_POSITIVE.'|max:999'
        ];  
    }

    protected function bind($input)
    {
        $this->id           = intval($input['id']);

        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;

        $this->height       = strval(round($input['height'],1));

        $this->gender       = intval($input['gender']);

        $this->weight       = strval(round($input['weight'],1));
    }
}