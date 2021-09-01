<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class PatientValueSearchForm extends BaseForm
{
    public $organization_id;
    public $patient_code;
    public $registered_user_id;
    public $registered_at_from;
    public $registered_at_to;
    public $page;

    protected function validationRule()
    {
        return [
            'organization_id'    => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'patient_code'       => 'nullable|' . Rule::VALUE_NAME,
            'registered_user_id' => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'registered_at_from' => 'nullable|date',
            'registered_at_to'   => 'nullable|date',
            'page'               => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->organization_id = isset($input['organization_id']) ? intval($input['organization_id']) : null;
        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;
        $this->registered_user_id = isset($input['registered_user_name']) ? strval($input['registered_user_name']) : null;

        $this->registered_at_from = isset($input['registered_at_from']) 
        ? Support\DateUtil::parseToDate($input['registered_at_from']) 
        : null;
        
        $this->registered_at_to = isset($input['registered_at_to']) 
        ? Support\DateUtil::parseToDate($input['registered_at_to']) 
        : null;
        
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}