<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

use App\Http\Forms\Api as Form;

class CalcIeSoundForm extends BaseForm
{
    public $sound;

    protected function validationRule()
    {
        return [
            'sound' => 'required|array',
        ];
    }

    protected function bind($input)
    {
        $this->sound = new Form\WaveForm($input['sound']);
    }
}
