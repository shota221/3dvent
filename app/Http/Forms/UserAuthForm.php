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
            'name' => 'required|'.Rule::VALUE_NAME,
            'organization_code' => 'required|'.Rule::VALUE_STRING,
            'password'  => 'required|'.'required|' . Rule::PASSWORD,
        ];  
    }

    protected function bind($input)
    {
        $this->name = $input['name'];
        $this->organization_code = $input['organization_code'];
        $this->password = $input['password'];
    }
}