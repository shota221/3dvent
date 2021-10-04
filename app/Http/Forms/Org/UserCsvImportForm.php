<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class UserCsvImportForm extends BaseForm
{
    public $csv_file;

    protected function validationRule()
    {
        return [
            'csv_file' => 'required|' .Rule::CSV_FILE,
        ];
    }

    protected function bind($input)
    {
        $this->csv_file = $input['csv_file'];
    }
}