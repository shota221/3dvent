<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class VentilatorValueUpdateForm extends BaseForm
{
    public $id;
    public $height;
    public $weight;
    public $gender;
    public $airway_pressure;
    public $air_flow;
    public $o2_flow;
    public $status_use;
    public $status_use_other;
    public $spo2;
    public $etco2;
    public $pao2;
    public $paco2;
    public $confirmed_flg;

    protected function validationRule()
    {
        return [
            'id'                     => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'height'                 => 'required|' . Rule::VALUE_POSITIVE,
            'weight'                 => 'nullable|' . Rule::VALUE_POSITIVE,
            'gender'                 => 'required|' . Rule::GENDER_INTEGER,
            'airway_pressure'        => 'required|'.Rule::VALUE_POSITIVE.'|between:8,45',
            'air_flow'               => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',
            'o2_flow'                => 'required|'.Rule::VALUE_POSITIVE.'|between:0,30',
            'status_use'             => 'nullable|' . Rule::STATUS_USE_INTEGER,
            'status_use_other'       => 'nullable|' . Rule::VALUE_STRING,
            'spo2'                   => 'nullable|'.Rule::VALUE_POSITIVE,
            'etco2'                  => 'nullable|'.Rule::VALUE_POSITIVE,
            'pao2'                   => 'nullable|'.Rule::VALUE_POSITIVE,
            'paco2'                  => 'nullable|'.Rule::VALUE_POSITIVE,
            'confirmed_flg'          => 'nullable|' . Rule::FLG_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);
        $this->height = strval($input['height']);
        $this->weight = isset($input['weight']) ? strval($input['weight']) : '';
        $this->gender = intval($input['gender']);
        $this->airway_pressure = strval($input['airway_pressure']);
        $this->air_flow = strval($input['air_flow']);
        $this->o2_flow = strval($input['o2_flow']);
        $this->status_use = isset($input['status_use']) ? intval($input['status_use']) : null;
        $this->status_use_other = isset($input['status_use_other']) ? strval($input['status_use_other']) : '';
        $this->spo2 = isset($input['spo2']) ? strval($input['spo2']) : '';
        $this->etco2 = isset($input['etco2']) ? strval($input['etco2']) : '';
        $this->pao2 = isset($input['pao2']) ? strval($input['pao2']) : '';
        $this->paco2 = isset($input['paco2']) ? strval($input['paco2']) : '';
        $this->confirmed_flg = isset($input['confirmed_flg']) ? intval($input['confirmed_flg']) : null;
    }
}
