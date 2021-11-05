<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorCsvImportForm extends BaseForm
{
    public $organization_id;
    public $csv_file;

    protected function validationRule()
    {
        return [
            'organization_id' => 'required|'.Rule::VALUE_POSITIVE_NON_ZERO,
            'csv_file' => 'required|'.Rule::VENTILATOR_DATA_CSV_FILE
        ];
    }

    protected function bind($input)
    {
        $this->organization_id = intval($input['organization_id']);
        $this->csv_file = $input['csv_file'];
    }
}
