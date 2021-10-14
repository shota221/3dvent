<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

class UserApplyPasswordResetForm extends BaseForm 
{
    public $email;

    protected function validationRule() { 
        return [
            'email' => 'required|' . Rule::EMAIL,
        ];
    }

    protected function bind($input)
    {
        $this->email = $input['email'];
    }
}