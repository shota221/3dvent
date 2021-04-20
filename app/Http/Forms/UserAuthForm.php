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
            //アカウント名は「{ユーザー名}@{組織コード}」の形式で入力される
            'name' => 'required|regex:/^.+@.+$/',
            'password'  => 'required|' . Rule::PASSWORD,
        ];
    }

    protected function bind($input)
    {
        $acount_name = explode('@', $input['name']);
        $this->name = $acount_name[0];
        $this->organization_code = $acount_name[1];
        $this->password = $input['password'];
    }
}
