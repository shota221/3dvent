<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class OrganizationSearchForm extends BaseForm
{
    public $organization_name;
    public $representative_name;
    public $organization_code;
    public $disabled_flg;
    public $edc_linked_flg;
    public $patient_obs_approved_flg;
    public $registered_at_from;
    public $registered_at_to;
    public $page;

    protected function validationRule()
    {
        return [
            'organization_name' => 'nullable|' . Rule::VALUE_NAME,
            'representative_name' => 'nullable|' . Rule::VALUE_NAME,
            'organization_code' => 'nullable|' . Rule::VALUE_CODE,
            'disabled_flg' => 'nullable|array',
            'edc_linked_flg' => 'nullable|array',
            'patient_obs_approved_flg' => 'nullable|array',
            'disabled_flg.*' => 'nullable|' . Rule::FLG_INTEGER,
            'edc_linked_flg.*' => 'nullable|' . Rule::FLG_INTEGER,
            'patient_obs_approved_flg.*' => 'nullable|' . Rule::FLG_INTEGER,
            'registered_at_from' => 'nullable|date',
            'registered_at_to' => 'nullable|date',
            'page' => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->organization_name = isset($input['organization_name']) ? strval($input['organization_name']) : null;
        $this->representative_name = isset($input['representative_name']) ? strval($input['representative_name']) : null;
        $this->organization_code = isset($input['organization_code']) ? strval($input['organization_code']) : null;
        $this->disabled_flg = !empty($input['disabled_flg']) ? $input['disabled_flg'] : null;
        $this->edc_linked_flg = !empty($input['edc_linked_flg']) ? $input['edc_linked_flg'] : null;
        $this->patient_obs_approved_flg = !empty($input['patient_obs_approved_flg']) ? $input['patient_obs_approved_flg'] : null;
        $this->registered_at_from = isset($input['registered_at_from']) ? Support\DateUtil::parseToDate($input['registered_at_from']) : null;
        $this->registered_at_to = isset($input['registered_at_to']) ? Support\DateUtil::parseToDate($input['registered_at_to']) : null;
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}
