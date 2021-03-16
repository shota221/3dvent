<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class AppkeyCreateForm extends BaseForm
{
    public $idfv;
    
    protected function validationRule()
    {
        return [
            'idfv' => 'required|'.Rule::VALUE_NAME,
        ]; 
    }

    protected function bind($input)
    {
        $this->idfv = $input['idfv'];
    }
}