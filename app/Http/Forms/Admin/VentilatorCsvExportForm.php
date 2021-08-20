<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorCsvExportForm extends BaseForm
{
    public $ids;

    protected function validationRule()
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'required|'.Rule::VALUE_POSITIVE_NON_ZERO
        ];
    }

    protected function bind($input)
    {
        $this->ids = $input['ids'];
    }
}
