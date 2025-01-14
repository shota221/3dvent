<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;
use App\Services\Support\Gs1Util;

class VentilatorCreateForm extends BaseForm
{
    public $gs1_code;

    public $latitude;

    public $longitude;

    protected function validationRule()
    {
        return [
            'gs1_code' => 'required|'.Rule::VALUE_STRING,

            'latitude' => 'nullable|numeric',

            'longitude' => 'nullable|numeric'
        ];  
    }

    protected function bind($input)
    {
        $this->gs1_code = Gs1Util::sanitizeGs1Code($input['gs1_code']);

        $this->latitude = $input['latitude'] ?? null;

        $this->longitude = $input['longitude'] ?? null;
    }
}