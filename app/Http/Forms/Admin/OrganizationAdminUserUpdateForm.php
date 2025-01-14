<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class OrganizationAdminUserUpdateForm extends BaseForm
{
    public $id;
    public $code;
    public $name;
    public $email;
    public $disabled_flg;
    public $password_changed;
    public $password;
    public $password_confirmation;

    protected function validationRule()
    {
        return [
            'id'                    => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
            'code'                  => 'required|' . Rule::VALUE_NAME,
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'required|' . Rule::EMAIL,
            'disabled_flg'          => 'required|' . Rule::FLG_INTEGER,
            'password_changed'      => 'required|' . Rule::VALUE_BOOLEAN,
            'password'              => 'nullable|' . Rule::PASSWORD,
            'password_confirmation' => 'nullable',
        ];
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);
        $this->code = strval($input['code']);
        $this->name = strval($input['name']);
        $this->email = strval($input['email']);
        $this->disabled_flg = intval($input['disabled_flg']);
        $this->password_changed = boolval($input['password_changed']);
        
        if ($this->password_changed) {
            $this->password = (isset($input['password']) && ! empty($input['password'])) 
            ?  strval($input['password']) 
            : null;
            
            $this->password_confirmation = (isset($input['password_confirmation']) && ! empty($input['password_confirmation'])) 
            ? strval($input['password_confirmation']) 
            : null;
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