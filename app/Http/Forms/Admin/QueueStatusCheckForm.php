<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\BaseForm;

class QueueStatusCheckForm extends BaseForm {
    
    public $queue;
    
    protected function validationRule() {
        return [
            'queue' => 'required|string',
        ];
    }

    protected function bind($inputs)
    {
        $this->queue = $inputs['queue'];
    }
}