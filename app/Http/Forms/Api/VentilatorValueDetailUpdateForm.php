<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueDetailUpdateForm extends BaseForm
{
    public $ventilator_value_id;

    public $registered_at;

    public $gender;

    public $height;

    public $weight;

    public $airway_pressure;

    public $air_flow;

    public $o2_flow;

    public $status_use;

    public $status_use_other;

    public $spo2;

    public $etco2;

    public $pao2;

    public $paco2;


    protected function validationRule()
    {
        return [
            'ventilator_value_id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,
            'registered_at' => 'nullable|'.Rule::VALUE_POSITIVE,
            'gender' => 'nullable|in:1,2',
            'height' => 'nullable|'.Rule::VALUE_POSITIVE,
            'weight' => 'nullable|'.Rule::VALUE_POSITIVE,
            'airway_pressure' => 'nullable|'.Rule::VALUE_POSITIVE,
            'air_flow' => 'nullable|'.Rule::VALUE_POSITIVE,
            'o2_flow' => 'nullable|'.Rule::VALUE_POSITIVE,
            'status_use' => 'nullable|in:0,1,2,3',
            'status_use_other' => 'nullable|'.Rule::VALUE_POSITIVE,
            'spo2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'etco2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'pao2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'paco2' => 'nullable|'.Rule::VALUE_POSITIVE,
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_value_id = $input['ventilator_value_id'];

        $this->registered_at = $input['registered_at'] ?? null;

        $this->gender = $input['gender'] ?? null;

        $this->height = $input['height'] ?? null;

        $this->weight = $input['weight'] ?? null;

        $this->airway_pressure = $input['airway_pressure'] ?? null;

        $this->air_flow = $input['air_flow'] ?? null;

        $this->o2_flow = $input['o2_flow'] ?? null;

        $this->status_use = $input['status_use'] ?? null;

        $this->status_use_other = $input['status_use_other'] ?? null;

        $this->spo2 = $input['spo2'] ?? null;

        $this->etco2 = $input['etco2'] ?? null;

        $this->pao2 = $input['pao2'] ?? null;

        $this->paco2 = $input['paco2'] ?? null;
    }
}
