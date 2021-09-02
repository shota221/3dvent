<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorCsvImportForm extends BaseForm
{
    public $organization_id;

    protected function validationRule()
    {
        return [
            'organization_id' => 'required|'.Rule::VALUE_POSITIVE_NON_ZERO,
            'csv_file' => 'required|max:1024|mimes:csv,txt'
        ];
    }

    protected function bind($input)
    {
        $this->organization_id = intval($input['organization_id']);
    }
}
