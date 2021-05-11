<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueUpdateForm extends BaseForm
{
    public $id;

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
            'id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,
            'registered_at' => 'required|date',
            'gender' => 'required|in:1,2',
            'height' => 'required|'.Rule::VALUE_POSITIVE,
            'weight' => 'nullable|'.Rule::VALUE_POSITIVE,
            'airway_pressure' => 'required|'.Rule::VALUE_POSITIVE,
            'air_flow' => 'required|'.Rule::VALUE_POSITIVE,
            'o2_flow' => 'required|'.Rule::VALUE_POSITIVE,
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

        $this->registered_at = $input['registered_at'];

        $this->gender = $input['gender'];

        $this->height = $input['height'];

        $this->weight = $input['weight'] ?? null;

        $this->airway_pressure = $input['airway_pressure'];

        $this->air_flow = $input['air_flow'];

        $this->o2_flow = $input['o2_flow'];

        $this->status_use = $input['status_use'] ?? null;

        $this->status_use_other = $input['status_use_other'] ?? null;

        $this->spo2 = $input['spo2'] ?? null;

        $this->etco2 = $input['etco2'] ?? null;

        $this->pao2 = $input['pao2'] ?? null;

        $this->paco2 = $input['paco2'] ?? null;
    }
}
