<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueShowForm extends BaseForm
{
    public $ventilator_id;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = $input['ventilator_id'];
    }
}
