<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Models;
use App\Services\Support;

class UserUpdateForm extends BaseForm
{
    public $id;
    public $name;
    public $email;
    public $authority_type;
    public $disabled_flg;
    public $password_changed;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'id'                    => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'nullable|required_if:authority_type,' . Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE . '|' . Rule::EMAIL,
            'authority_type'        => 'required|' . Rule::ORG_AUTHORITY_TYPE,
            'disabled_flg'          => 'required|' . Rule::FLG_INTEGER,
            'password_changed'      => 'required|' . Rule::VALUE_BOOLEAN,
            'password'              => 'nullable|' . Rule::PASSWORD,
            'password_confirmation' => 'nullable',
        ];
    }

    protected function bind($input)
    {
        $this->id               = intval($input['id']);
        $this->name             = strval($input['name']);
        $this->email            = isset($input['email']) ? strval($input['email']) : '';
        $this->authority_type   = intval($input['authority_type']);
        $this->disabled_flg     = intval($input['disabled_flg']);
        $this->password_changed = boolval($input['password_changed']);
        
        if ($this->password_changed) {
            $existsPassword = isset($input['password']) && ! empty($input['password']); 
            $this->password = $existsPassword ? strval($input['password']) : null;
            
            $existsPasswordConfirmation = isset($input['password_confirmation']) && ! empty($input['password_confirmation']);
            $this->password_confirmation = $existsPasswordConfirmation ? strval($input['password_confirmation']) : null;
        } else {
            $this->password = null;
            $this->password_confirmation = null;
        }
    }

    protected function validateAfterBinding() {
        if ($this->password_changed && is_null($this->password)) {
            $this->addError('password', 'validation.required_password');
        }
        
        if ($this->password_changed && is_null($this->password_confirmation)) {
            $this->addError('password_confirmation', 'validation.required_password_confirmation');
        }
        
        if ($this->password_changed && ($this->password !== $this->password_confirmation)) {
            $this->addError('password', 'validation.password_confirmed');
        }
    }
}