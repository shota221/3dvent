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
    public $org_authority_type;
    public $disabled_flg;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'nullable|' . Rule::EMAIL,
            'org_authority_type'    => 'required|' . Rule::ORG_AUTHORITY_TYPE,
            'disabled_flg'          => 'required|' . Rule::FLG_INTEGER,
            'password'              => 'required|confirmed|' . Rule::PASSWORD,
            'password_confirmation' => 'required',
        ];
    }

    protected function bind($input)
    {
        $this->name               = strval($input['name']);
        $this->email              = isset($input['email']) ? strval($input['email']) : '';
        $this->org_authority_type = intval($input['org_authority_type']);
        $this->disabled_flg       = intval($input['disabled_flg']);
        $this->password           = strval($input['password']);
    }

    protected function validateAfterBinding() {
        if ($this->org_authority_type === Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE) {
            if (empty($this->email)) {
                $this->addError('email', 'validation.required_for_principal_investigator');
            }
        }
    }
}