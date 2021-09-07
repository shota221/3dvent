<?php 

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class PatientValueSearchForm extends BaseForm
{
    public $patient_code;
    public $registered_user_name;
    public $registered_at_from;
    public $registered_at_to;
    public $page;

    protected function validationRule()
    {
        return [
            'patient_code'         => 'nullable|' . Rule::VALUE_NAME,
            'registered_user_name' => 'nullable|' . Rule::VALUE_NAME,
            'registered_at_from'   => 'nullable|date',
            'registered_at_to'     => 'nullable|date',
            'page'                 => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;
        $this->registered_user_name = isset($input['registered_user_name']) ? strval($input['registered_user_name']) : null;

        $this->registered_at_from = isset($input['registered_at_from']) 
        ? Support\DateUtil::ParseToDate($input['registered_at_from']) 
        : null;
        
        $this->registered_at_to = isset($input['registered_at_to']) 
        ? Support\DateUtil::ParseToDate($input['registered_at_to']) 
        : null;
        
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}