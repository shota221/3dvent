<?php

namespace App\Http\Forms\Admin;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class OrganizationAdminUserSearchForm extends BaseForm
{
    public $organization_name;
    public $name;
    public $registerd_at_from;
    public $registerd_at_to;
    public $page;
    public $http_query;

    protected function validationRule()
    {
        return [
            'organization_name'  => 'nullable|' . Rule::VALUE_NAME,
            'name'               => 'nullable|' . Rule::VALUE_NAME,
            'registered_at_from' => 'nullable|date',
            'registered_at_to'   => 'nullable|date',
            'page'               => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->organization_name = isset($input['organization_name']) ? strval($input['organization_name']) : null;
        $this->name = isset($input['name']) ? strval($input['name']) : null;

        try {
            $this->registerd_at_from = isset($input['registerd_at_from']) 
            ? Support\DataUtil::parseToDate($input['registerd_at_from']) 
            : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('registered_at_from', 'validation.date');
        }

        try {
            $this->registerd_at_to = isset($input['registerd_at_to']) 
            ? Support\DataUtil::parseToDate($input['registerd_at_to']) 
            : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('registered_at_to', 'validation.date');
        }

        $this->page = isset($input['page']) ? intval($input['page']) : null;
        $this->http_query = isset($input) ? '?' . http_build_query($input) : '';
    }
}