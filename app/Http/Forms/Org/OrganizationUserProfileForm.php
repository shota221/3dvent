<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class OrganizationUserProfileForm extends BaseForm
{
    public $name;
    public $email;

    protected function validationRule()
    {
        return [
            'name'                  => 'required|' . Rule::VALUE_NAME,
            'email'                 => 'nullable|' . Rule::EMAIL,
            'password_changed'      => 'required|' . Rule::VALUE_BOOLEAN,
            'password'              => 'nullable|' . Rule::PASSWORD,
            'password_confirmation' => 'nullable',
        ];
    }

    protected function bind($input)
    {
        $this->name             = strval($input['name']);
        $this->email            = isset($input['email']) ? strval($input['email']) : '';
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