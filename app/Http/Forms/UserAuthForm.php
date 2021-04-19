<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class UserAuthForm extends BaseForm
{
    public $name;

    public $organization_code;

    public $password;
    
    protected function validationRule()
    {
        return [
            'name' => 'required|regex:/^.+@.+$/',
            'password'  => 'required|'.'required|' . Rule::PASSWORD,
        ];  
    }

    protected function bind($input)
    {
        $login_name = explode('@',$input['name']);
        $this->name = $login_name[0];
        $this->organization_code = $login_name[1];
        $this->password = $input['password'];
    }
}