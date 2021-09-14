<?php

namespace App\Http\Forms\Org;

use App\Exceptions;
use App\Http\Forms\BaseForm;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support;

class VentilatorValueSearchForm extends BaseForm
{
    public $ventilator_id;
    public $gs1_code;
    public $patient_code;
    public $registered_user_name;
    public $registered_at_from;
    public $registered_at_to;
    public $fixed_flg;
    public $confirmed_flg;
    public $page;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'nullable|' .Rule::VALUE_POSITIVE_NON_ZERO,
            'gs1_code'      => 'nullable|' . Rule::VALUE_NAME,
            'patient_code'         => 'nullable|' . Rule::VALUE_NAME,
            'registered_user_name' => 'nullable|' . Rule::VALUE_NAME,
            'registered_at_from'   => 'nullable|date',
            'registered_at_to'     => 'nullable|date',
            'fixed_flg'            => 'nullable|' . Rule::FLG_INTEGER,
            'confirmed_flg'        => 'nullable|array',
            'confirmed_flg.*'      => 'nullable|' . Rule::FLG_INTEGER,
            'page'                 => 'nullable|' . Rule::VALUE_POSITIVE_NON_ZERO,
        ];
    }

    protected function bind($input)
    {
        $this->ventilator_id = isset($input['ventilator_id']) ? intval($input['ventilator_id']) : null;
        $this->gs1_code = isset($input['gs1_code']) ? strval($input['gs1_code']) : null;
        $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;
        $this->registered_user_name = isset($input['registered_user_name']) ? strval($input['registered_user_name']) : null;
        $this->registered_at_from = isset($input['registered_at_from']) ? Support\DateUtil::parseToDate($input['registered_at_from']) : null;
        $this->registered_at_to = isset($input['registered_at_to']) ? Support\DateUtil::parseToDate($input['registered_at_to']) : null;
        $this->fixed_flg = isset($input['fixed_flg']) ? intval($input['fixed_flg']) : null;
        $this->confirmed_flg = isset($input['confirmed_flg']) ? $input['confirmed_flg'] : null;
        $this->page = isset($input['page']) ? intval($input['page']) : null;
    }
}
