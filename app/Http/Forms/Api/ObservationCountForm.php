<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

use App\Services\Support;

class ObservationCountForm extends BaseForm
{
    public $edcid;

    public $date_from;

    public $date_to;

    protected function validationRule()
    {
        return [
            'edcid'     => 'required|' . Rule::VALUE_STRING,
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
        ];
    }

    protected function bind($input)
    {
        $this->edcid = $input['edcid'];

        try {
            $this->date_from = isset($input['date_from']) 
                ? Support\DateUtil::dayStart(Support\DateUtil::parseToDate($input['date_from'])) 
                : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('date_from', 'validation.date');
        }

        try {
            $this->date_to = isset($input['date_to']) 
                ? Support\DateUtil::dayEnd(Support\DateUtil::parseToDate($input['date_to'])) 
                : null;
        } catch (Exceptions\DateUtilException $e) {
            $this->addError('date_to', 'validation.date');
        }

    }
}
