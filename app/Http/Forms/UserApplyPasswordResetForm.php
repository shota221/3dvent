<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

class UserApplyPasswordResetForm extends BaseForm 
{
    public $code;
    public $email;

    protected function validationRule() { 
        return [
            'code'  => 'required|' .Rule::VALUE_CODE,
            'email' => 'required|' . Rule::EMAIL,
        ];
    }

    protected function bind($input)
    {
        $this->code  = strval($input['code']);
        $this->email = strval($input['email']);
    }
}