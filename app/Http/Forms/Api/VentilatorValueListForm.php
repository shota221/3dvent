<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueListForm extends BaseForm
{
    public $ventilator_id;

    public $limit;

    public $offset;

    public $fixed_flg;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|' . Rule::VALUE_POSITIVE_INTEGER,
            'limit' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'offset' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'fixed_flg' => 'nullable|'.Rule::FLG_INTEGER
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = intval($input['ventilator_id']);        

        $this->limit = isset($input['limit']) ? intval($input['limit']) : null; 

        $this->offset = isset($input['offset']) ? intval($input['offset']) : null;  

        $this->fixed_flg = isset($input['fixed_flg']) ? intval($input['fixed_flg']) : null;  
    }
}
