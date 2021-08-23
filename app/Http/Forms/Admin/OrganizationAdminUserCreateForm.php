<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class OrganizationAdminUserCreateForm extends BaseForm
{
    public $code;
    public $name;
    public $email;
    public $disabled_flg;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'code'                  => 'required|' . Rule::VALUE_NAME,
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'required|' . Rule::EMAIL,
            'disabled_flg'          => 'required|' .Rule::FLG_INTEGER,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->code = strval($input['code']);
        $this->name = strval($input['name']);
        $this->email = strval($input['email']);
        $this->disabled_flg = intval($input['disabled_flg']);
        $this->password = strval($input['password']);
    }
}