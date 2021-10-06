<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;

class UserLogicalDeleteForm extends BaseForm
{
    public $ids;

    protected function validationRule()
    {
        return [
            'ids'    => 'required|array',
            'ids.* ' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->ids = $input['ids'];
    }
}