<?php

namespace App\Http\Forms\Manual;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;

class CalcFio2Form extends BaseForm
{
    public $air_flow;
    public $o2_flow;
    
    protected function validationRule()
    {
        return [        
            'air_flow' => 'required|' .Rule::VALUE_POSITIVE. '|between:0,30',
            'o2_flow'  => 'required|' .Rule::VALUE_POSITIVE. '|between:0,30',
        ];  
    }

    protected function bind($input)
    {
        $this->air_flow = isset($input['air_flow']) ? strval(round($input['air_flow'], 1)) : null;
        $this->o2_flow  = isset($input['o2_flow'])  ? strval(round($input['o2_flow'], 1))  : null;    
    }
}