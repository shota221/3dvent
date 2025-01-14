<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueUpdateForm extends BaseForm
{
    public $id;

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
            'id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,
            'gender' => 'required|in:1,2',
            'height' => 'required|'.Rule::VALUE_POSITIVE,
            'weight' => 'required|'.Rule::VALUE_POSITIVE,
            'airway_pressure' => 'required|'.Rule::VALUE_POSITIVE.'|between:8,45',
            'air_flow' => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',
            'o2_flow' => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',
            'status_use' => 'nullable|in:1,2,3,4',
            'status_use_other' => 'nullable|'.Rule::VALUE_STRING,
            'spo2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'etco2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'pao2' => 'nullable|'.Rule::VALUE_POSITIVE,
            'paco2' => 'nullable|'.Rule::VALUE_POSITIVE,
        ];
    }

    protected function bind($input)
    {
        $this->id = $input['id'];

        $this->gender = $input['gender'];

        $this->height = $input['height'];

        $this->weight = $input['weight'];

        $this->airway_pressure = $input['airway_pressure'];

        $this->air_flow = $input['air_flow'];

        $this->o2_flow = $input['o2_flow'];

        $this->status_use = $input['status_use'] ?? null;

        $this->status_use_other = $input['status_use_other'] ?? '';

        $this->spo2 = $input['spo2'] ?? '';

        $this->etco2 = $input['etco2'] ?? '';

        $this->pao2 = $input['pao2'] ?? '';

        $this->paco2 = $input['paco2'] ?? '';
    }
}
