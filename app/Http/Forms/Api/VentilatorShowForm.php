<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;
use App\Services\Support\Gs1Util;

class VentilatorShowForm extends BaseForm
{
    public $gs1_code;

    protected function validationRule()
    {
        return [
            'gs1_code' => 'required|' . Rule::VALUE_STRING,
        ];
    }

    protected function bind($input)
    {
        $this->gs1_code = Gs1Util::sanitizeGs1Code($input['gs1_code']);
    }
}
