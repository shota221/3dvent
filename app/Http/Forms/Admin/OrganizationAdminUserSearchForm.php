<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class OrganizationAdminUserSearchForm extends BaseForm
{
    public $organization_id;
    public $name;
    public $registerd_at_from;
    public $registerd_at_to;
    public $disabled_flg;
    public $page;

    protected function validationRule()
    {
        return [
            'organization_id'    => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'name'               => 'nullable|' . Rule::VALUE_NAME,
            'registered_at_from' => 'nullable|date',
            'registered_at_to'   => 'nullable|date',
            'disabled_flg'       => 'nullable|' . Rule::FLG_INTEGER,
            'page'               => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->organization_id = isset($input['organization_id']) ? strval($input['organization_id']) : null;
        $this->name = isset($input['name']) ? strval($input['name']) : null;
        
        $this->registered_at_from = isset($input['registered_at_from']) 
        ? Support\DateUtil::parseToDate($input['registered_at_from']) 
        : null;

        $this->registered_at_to = isset($input['registered_at_to']) 
        ? Support\DateUtil::parseToDate($input['registered_at_to']) 
        : null;
        
        $this->disabled_flg = isset($input['disabled_flg']) ? $input['disabled_flg'] : null;
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}