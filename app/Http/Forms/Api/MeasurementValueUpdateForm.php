<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class MeasurementValueUpdateForm extends BaseForm
{
    public $id;

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
            'status_use' => 'nullable|'.Rule::STATUS_USE_INTEGER,
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

        $this->status_use = $input['status_use'] ?? null;

        $this->status_use_other = $input['status_use_other'] ?? '';

        $this->spo2 = $input['spo2'] ?? '';

        $this->etco2 = $input['etco2'] ?? '';

        $this->pao2 = $input['pao2'] ?? '';

        $this->paco2 = $input['paco2'] ?? '';
    }
}
