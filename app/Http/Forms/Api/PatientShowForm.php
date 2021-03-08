<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientShowForm extends BaseForm
{
    public $id;
    
    protected function validationRule()
    {
        return [
            'id' => 'required|'.Rule::VALUE_INTEGER,
        ];  
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);
    }
}