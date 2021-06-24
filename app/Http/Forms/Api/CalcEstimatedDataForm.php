<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class CalcEstimatedDataForm extends BaseForm
{
    public $patient_id;

    public $airway_pressure;

    public $air_flow;

    public $o2_flow;
    
    protected function validationRule()
    {
        return [        
            'airway_pressure' => 'required|'.Rule::VALUE_POSITIVE.'|between:8,45',

            'air_flow' => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',

            'o2_flow' => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',
        ];  
    }

    protected function bind($input)
    {
        $this->airway_pressure = isset($input['airway_pressure']) ? strval(round($input['airway_pressure'],1)) : null;

        $this->air_flow = isset($input['air_flow']) ? strval(round($input['air_flow'],1)) : null;

        $this->o2_flow = isset($input['o2_flow']) ? strval(round($input['o2_flow'],1)) : null;    
    }
}