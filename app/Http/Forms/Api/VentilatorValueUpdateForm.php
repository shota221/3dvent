<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueUpdateForm extends BaseForm
{
    public $ventilator_id;

    public $fixed_flg;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,

            'fixed_flg' => 'required|' . Rule::VALUE_BOOLEAN,
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = intval($input['ventilator_id']);

        $this->fixed_flg = intval($input['fixed_flg']);
    }
}
