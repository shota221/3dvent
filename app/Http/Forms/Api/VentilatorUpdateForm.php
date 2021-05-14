<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorUpdateForm extends BaseForm
{
    public $id;

    public $start_using_at;

    protected function validationRule()
    {
        return [
            'id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,

            'start_using_at' => 'date'
        ];
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);

        $this->start_using_at = isset($input['start_using_at']) ? $input['start_using_at'] : null;
    }
}
