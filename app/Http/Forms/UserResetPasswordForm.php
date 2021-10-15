<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

class UserResetPasswordForm extends BaseForm 
{
    public $token;
    public $code;
    public $email;
    public $password;
    public $password_confirmation;

    protected function validationRule() { 
        return [
            'token'                 => 'required',
            'code'                  => 'required|' .Rule::VALUE_CODE,
            'email'                 => 'required|' . Rule::EMAIL,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->token    = strval($input['token']);
        $this->code     = strval($input['code']);
        $this->email    = strval($input['email']);
        $this->password = strval($input['password']);
    }
}