<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class PatientValueForm extends BaseForm
{
    public $id;

    public $opt_out_flg;

    public $age;

    public $vent_disease_name;

    public $other_disease_name_1;

    public $other_disease_name_2;

    public $used_place;

    public $hospital;

    public $national;

    public $discontinuation_at;

    public $outcome;

    public $treatment;

    public $adverse_event_flg;

    public $adverse_event_contents;

    protected function validationRule()
    {
        return [
            'id' => 'required|'.Rule::VALUE_INTEGER,
            'opt_out_flg' => 'nullable|'.Rule::FLG_INTEGER,
            'age' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'vent_disease_name' => 'nullable|'.Rule::VALUE_STRING,
            'other_disease_name_1' => 'nullable|'.Rule::VALUE_STRING,
            'other_disease_name_2' => 'nullable|'.Rule::VALUE_STRING,
            'used_place' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'hospital' => 'nullable|'.Rule::VALUE_STRING,
            'national' => 'nullable|'.Rule::VALUE_STRING,
            'discontinuation_at' => 'nullable|date',
            'outcome' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'treatment' => 'nullable|'.Rule::VALUE_POSITIVE_INTEGER,
            'adverse_event_flg' => 'nullable|'.Rule::FLG_INTEGER,
            'adverse_event_contents' => 'nullable|'.Rule::VALUE_TEXT,
        ];  
    }

    protected function bind($input)
    {
        $this->id = intval($input['id']);

        $this->opt_out_flg = isset($input['opt_out_flg']) ? intval($input['opt_out_flg']) : null;

        $this->age = isset($input['age']) ? strval($input['age']) : null;

        $this->vent_disease_name = isset($input['vent_disease_name']) ? strval($input['vent_disease_name']) : null;

        $this->other_disease_name_1 = isset($input['other_disease_name_1']) ? strval($input['other_disease_name_1']) : null;

        $this->other_disease_name_2 = isset($input['other_disease_name_2']) ? strval($input['other_disease_name_2']) : null;

        $this->used_place = isset($input['used_place']) ? intval($input['used_place']) : null;

        $this->hospital = isset($input['hospital']) ? strval($input['hospital']) : null;

        $this->national = isset($input['national']) ? strval($input['national']) : null;

        $this->discontinuation_at = isset($input['discontinuation_at']) ? strval($input['discontinuation_at']) : null;

        $this->outcome = isset($input['outcome']) ? intval($input['outcome']) : null;

        $this->treatment = isset($input['treatment']) ? intval($input['treatment']) : null;

        $this->adverse_event_flg = isset($input['adverse_event_flg']) ? intval($input['adverse_event_flg']) : null;

        $this->adverse_event_contents = isset($input['adverse_event_contents']) ? strval($input['adverse_event_contents']) : null;   
    }
}