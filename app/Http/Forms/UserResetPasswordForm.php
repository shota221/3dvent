<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

class UserResetPasswordForm extends BaseForm 
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;

    protected function validationRule() { 
        return [
            'token'                 => 'required',
            'email'                 => 'required|' . Rule::EMAIL,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->token    = $input['token'];
        $this->email    = $input['email'];
        $this->password = $input['password'];
    }
}