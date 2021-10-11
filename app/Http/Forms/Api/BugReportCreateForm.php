<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class BugReportCreateForm extends BaseForm
{
    public $ventilator_id;

    public $bug_name;

    public $request_improvement;
    
    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|'.Rule::VALUE_POSITIVE_NON_ZERO,
        
            'bug_name' => 'required|'.Rule::VALUE_STRING,

            'request_improvement' => 'nullable|'.Rule::VALUE_TEXT
        ];  
    }

    protected function bind($input)
    {
        $this->ventilator_id = intval($input['ventilator_id']);

        $this->bug_name = strval($input['bug_name']);

        $this->request_improvement = isset($input['request_improvement']) ? strval($input['request_improvement']) : '';
    }
}