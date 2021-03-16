<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class WaveForm extends BaseForm
{
    public $filename;

    public $file_data;
    
    protected function validationRule()
    {
        return [        
            'content_type' => 'required|starts_with:audio/wav',
            'filename'=>'required|'.Rule::VALUE_STRING,
            'file_data'=>'required'
        ];  
    }

    protected function bind($input)
    {
        $this->filename = $input['filename'];

        $this->file_data = $input['file_data'];
    }
}