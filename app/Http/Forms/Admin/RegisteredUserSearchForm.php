<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class RegisteredUserSearchForm extends BaseForm
{
    public $organization_id;

    protected function validationRule()
    {
        return [
            'organization_id' => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->organization_id = intval($input['organization_id']);
    }
}