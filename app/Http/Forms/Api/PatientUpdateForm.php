<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientUpdateForm extends BaseForm
{
    public $id;

    public $nickname;

    public $height;

    public $gender;

    public $ideal_weight;

    public $other_attrs;
    
    protected function validationRule()
    {
        return [
            'id' => 'required|'.Rule::VALUE_INTEGER,

            'nickname' => 'nullable|'.Rule::VALUE_NAME,
        
            'height' => 'required|',Rule::VALUE_POSITIVE.'|max:999',
        
            'gender' => 'required|integer|min:1|max:2'
        ];  
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);

        $this->nickname = $input['nickname'] ?? '';

        $this->height = strval(round($input['height'],1));

        $this->gender = intval($input['gender']);

        $this->other_attrs = $input['other_attrs'] ?? null;
    }
}