<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class CalcIeManualElmForm extends BaseForm
{
    public $e;

    public $respirations_per_10sec;

    protected function validationRule()
    {
        return [
            'e'                      => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'respirations_per_10sec' => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->e = strval(round($input['e'], 3));
        $this->respirations_per_10sec = strval(round($input['respirations_per_10sec'], 3));
    }
}
