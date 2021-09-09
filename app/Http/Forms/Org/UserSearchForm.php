<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class UserSearchForm extends BaseForm
{
    public $name;
    public $authority;
    public $registered_at_from;
    public $registerd_at_to;
    public $disabled_flg;
    public $page;

    protected function validationRule()
    {
        return [
            'name'               => 'nullable|' . Rule::VALUE_NAME,
            'authority'          => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'registered_at_from' => 'nullable|date',
            'registered_at_to'   => 'nullable|date',
            'disabled_flg'       => 'nullable|array|',
            'disabled_flg*'      => 'nullable|' . Rule::FLG_INTEGER,
            'page'               => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->name = isset($input['name']) 
        ? strval($input['name']) 
        : null;

        $this->authority = isset($input['authority']) 
        ? intval($input['authority']) 
        : null;
        
        $this->registered_at_from = isset($input['registered_at_from']) 
        ? Support\DateUtil::parseToDate($input['registered_at_from']) 
        : null;

        $this->registered_at_to = isset($input['registered_at_to']) 
        ? Support\DateUtil::parseToDate($input['registered_at_to']) 
        : null;
        
        $this->disabled_flg = isset($input['disabled_flg']) 
        ? $input['disabled_flg'] 
        : null;
        
        $this->page = isset($input['page']) 
        ? intval($input['page']) 
        : null;
    }
}