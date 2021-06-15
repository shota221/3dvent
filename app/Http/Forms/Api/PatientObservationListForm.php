<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

use App\Services\Support;

class PatientObservationListForm extends BaseForm
{
    public $edcid;

    public $datetime_from;

    public $datetime_to;

    public $limit;

    public $offset;

    protected function validationRule()
    {
        return [
            'edcid'         => 'required|' . Rule::VALUE_STRING,
            'datetime_from' => 'nullable|date_format:Y-m-d H:i:s',
            'datetime_to'   => 'nullable|date_format:Y-m-d H:i:s',
            'limit'         => 'required|'.Rule::VALUE_POSITIVE_INTEGER .'|between:0,200',
            'offset'        => 'required|'.Rule::VALUE_POSITIVE_INTEGER,
        ];
    }

    protected function bind($input)
    {
        $this->edcid = $input['edcid'];
        
        try {
            $this->datetime_from = isset($input['datetime_from']) 
                ? Support\DateUtil::parseToDateTime($input['datetime_from']) 
                : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('datetime_from', 'validation.date');
        }

        try {
            $this->datetime_to = isset($input['datetime_to']) 
                ? Support\DateUtil::parseToDateTime($input['datetime_to']) 
                : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('datetime_to', 'validation.date');
        }

        $this->limit = intval($input['limit']);

        $this->offset = intval($input['offset']); 

    }
}