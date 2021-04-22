<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueDetailShowForm extends BaseForm
{
    public $ventilator_id;

    public $ventilator_value_id;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'ventilator_value_id' => 'nullable|' .Rule::VALUE_POSITIVE_INTEGER
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = isset($input['ventilator_id']) ? intval($input['ventilator_id']) : null;
        $this->ventilator_value_id = isset($input['ventilator_value_id']) ? intval($input['ventilator_value_id']) : null;
    }
}
