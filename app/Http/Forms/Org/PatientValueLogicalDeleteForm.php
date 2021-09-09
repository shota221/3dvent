<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;

class PatientValueLogicalDeleteForm extends BaseForm
{
    public $ids;

    protected function validationRule()
    {
        return [
            'ids'    => 'required|array|',
            'ids.* ' => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->ids = $input['ids'];
    }
}