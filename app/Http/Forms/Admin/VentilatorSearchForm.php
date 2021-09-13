<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorSearchForm extends BaseForm
{
    public $serial_number;
    public $organization_id;
    public $registered_user_name;
    public $expiration_date_from;
    public $expiration_date_to;
    public $start_using_at_from;
    public $start_using_at_to;
    public $has_bug;
    public $page;

    protected function validationRule()
    {
        return [
            'serial_number' => 'nullable',
            'organization_id' => 'nullable|' . Rule::VALUE_NAME,
            'registered_user_name' => 'nullable|' . Rule::VALUE_NAME,
            'expiration_date_from' => 'nullable|date',
            'expiration_date_to' => 'nullable|date',
            'start_using_at_from' => 'nullable|date',
            'start_using_at_to' => 'nullable|date',
            'has_bug' => 'nullable|array',
            'has_bug.*' => 'nullable|' . Rule::FLG_INTEGER,
            'page' => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->serial_number = isset($input['serial_number']) ? strval($input['serial_number']) : null;
        $this->organization_id = isset($input['organization_id']) ? intval($input['organization_id']) : null;
        $this->registered_user_name = isset($input['registered_user_name']) ? strval($input['registered_user_name']) : null;
        $this->expiration_date_from = isset($input['expiration_date_from']) ? Support\DateUtil::parseToDate($input['expiration_date_from']) : null;
        $this->expiration_date_to = isset($input['expiration_date_to']) ? Support\DateUtil::parseToDate($input['expiration_date_to']) : null;
        $this->start_using_at_from = isset($input['start_using_at_from']) ? Support\DateUtil::parseToDate($input['start_using_at_from']) : null;
        $this->start_using_at_to = isset($input['start_using_at_to']) ? Support\DateUtil::parseToDate($input['start_using_at_to']) : null;
        $this->has_bug = !empty($input['has_bug']) ? $input['has_bug'] : null;
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}
