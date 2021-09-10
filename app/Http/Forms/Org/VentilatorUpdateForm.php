<?php

namespace App\Http\Forms\Org;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorUpdateForm extends BaseForm
{
    public $id;
    public $start_using_at;

    protected function validationRule()
    {
        return [
            'id' => 'required|'.Rule::VALUE_POSITIVE_NON_ZERO,
            'start_using_at' => 'required|date'
        ];
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);
        $this->start_using_at = isset($input['start_using_at']) ? Support\DateUtil::parseToDate($input['start_using_at']) : null;
    }
}
