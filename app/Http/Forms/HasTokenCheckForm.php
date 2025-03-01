<?php

namespace App\Http\Forms;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class HasTokenCheckForm extends BaseForm
{
    public $name;

    public $organization_code;

    public $password;

    protected function validationRule()
    {
        return [
            //アカウント名は「{ユーザー名}@{組織コード}」の形式で入力される
            'name' => 'required|regex:/^.+@.+$/',
        ];
    }

    protected function bind($input)
    {
        $account_name = explode('@', $input['name']);
        $this->name = $account_name[0];
        $this->organization_code = $account_name[1];
    }
}
