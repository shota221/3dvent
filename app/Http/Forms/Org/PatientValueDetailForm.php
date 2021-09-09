<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class PatientValueDetailForm extends BaseForm
{
    public $id;

    protected function validationRule()
    {
        return [
            'id' => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);
    }
}