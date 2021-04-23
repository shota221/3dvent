<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueListShowForm extends BaseForm
{
    public $ventilator_id;

    public $limit;

    public $offset;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
            'limit' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'offset' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = intval($input['ventilator_id']);        

        $this->limit = isset($input['limit']) ? intval($input['limit']) : null; 

        $this->offset = isset($input['limit']) ? intval($input['offset']) : null;        
    }
}
