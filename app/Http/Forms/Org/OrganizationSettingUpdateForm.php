<?php

namespace App\Http\Forms\Org;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;

class OrganizationSettingUpdateForm extends BaseForm
{
    public $ventilator_value_scan_interval;

    public $vt_per_kg;

    protected function validationRule()
    {
        return [
            'ventilator_value_scan_interval' => 'required|'. Rule::VALUE_POSITIVE_INTEGER,
            'vt_per_kg'                      => 'required|'. Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_value_scan_interval = strval($input['ventilator_value_scan_interval']);
        $this->vt_per_kg = strval($input['vt_per_kg']);
    }
}