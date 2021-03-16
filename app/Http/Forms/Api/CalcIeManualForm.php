<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

use App\Http\Forms\Api as Form;

class CalcIeManualForm extends BaseForm
{
    public $data;
    
    protected function validationRule()
    {
        return [        
            'data' => 'required|array',
        ];  
    }

    protected function bind($input)
    {
        foreach ($input['data'] as $i_e) {
            $this->data[] = new Form\IeForm($i_e);         
        }
    }
}