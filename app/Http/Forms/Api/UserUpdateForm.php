<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class UserUpdateForm extends BaseForm
{
    public $name;

    public $email;

    protected function validationRule()
    {
        return [
            'user_name' => 'required|' . Rule::VALUE_NAME . '|' . Rule::stringExclude('@'),

            'email' => 'nullable|email',
        ];
    }

    protected function bind($input)
    {
        $this->name = $input['user_name'];

        $this->email = $input['email'] ?? null;
    }
}
