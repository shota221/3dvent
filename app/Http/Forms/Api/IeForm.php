<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class IeForm extends BaseForm
{
    public $i;

    public $e;
    
    protected function validationRule()
    {
        return [        
            'i' => 'required|'.Rule::VALUE_POSITIVE,

            'e' => 'required|'.Rule::VALUE_POSITIVE,
        ];  
    }

    protected function bind($input)
    {
        $this->i = strval(round($input['i'],3));

        $this->e = strval(round($input['e'],3));
    }
}