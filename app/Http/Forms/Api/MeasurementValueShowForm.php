<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class MeasurementValueShowForm extends BaseForm
{
    public $id;

    protected function validationRule()
    {
        return [
            'id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->id = $input['id'];
    }
}
