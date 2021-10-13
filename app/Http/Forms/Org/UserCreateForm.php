<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Models;
use App\Services\Support;


class UserCreateForm extends BaseForm
{
    public $name;
    public $email;
    public $authority_type;
    public $disabled_flg;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'nullable|required_if:authority_type,' . Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE . '|' . Rule::EMAIL,
            'authority_type'        => 'required|' . Rule::ORG_AUTHORITY_TYPE,
            'disabled_flg'          => 'required|' .Rule::FLG_INTEGER,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->name           = strval($input['name']);
        $this->email          = isset($input['email']) ? strval($input['email']) : '';
        $this->authority_type = intval($input['authority_type']);
        $this->disabled_flg   = intval($input['disabled_flg']);
        $this->password       = strval($input['password']);
    }
}