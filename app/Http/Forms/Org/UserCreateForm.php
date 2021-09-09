<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class UserCreateForm extends BaseForm
{
    public $name;
    public $email;
    public $authority;
    public $disabled_flg;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'name'                  => 'required|' . Rule::VALUE_NAME,
            // 権限回り実装後に修正
            'email'                 => 'nullable|required_if:authority,1|' . Rule::EMAIL,
            'authority'             => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
            'disabled_flg'          => 'required|' .Rule::FLG_INTEGER,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->name         = strval($input['name']);
        $this->email        = isset($input['email']) ? strval($input['email']) : '';
        $this->authority    = intval($input['authority']);
        $this->disabled_flg = intval($input['disabled_flg']);
        $this->password     = strval($input['password']);
    }
}