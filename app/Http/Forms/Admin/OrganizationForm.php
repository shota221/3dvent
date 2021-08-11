<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class OrganizationForm extends BaseForm
{
    public $organization_name;
    public $representative_name;
    public $representative_email;
    public $organization_code;
    public $disabled_flg;
    public $edcid;
    public $patient_obs_approved_flg;
    
    protected function validationRule()
    {
        return [
            'organization_name' => 'required|'.Rule::VALUE_NAME,
            'representative_name' => 'required|'.Rule::VALUE_NAME,
            'representative_email' => 'required|'.Rule::EMAIL,
            'organization_code' => 'required|'.Rule::VALUE_CODE,
            'disabled_flg' => 'required|'.Rule::FLG_INTEGER,
            'edcid' => 'nullable|'.Rule::VALUE_NAME,
            'patient_obs_approved_flg' => 'required|'.Rule::FLG_INTEGER
        ]; 
    }

    protected function bind($input)
    {
        $this->organization_name = strval($input['organization_name']);
        $this->representative_name = strval($input['representative_name']);
        $this->representative_email = strval($input['representative_email']);
        $this->organization_code = strval($input['organization_code']);
        $this->disabled_flg = intval($input['disabled_flg']);
        $this->edcid = isset($input['edcid']) ? strval($input['edcid']) : null;
        $this->patient_obs_approved_flg = intval($input['patient_obs_approved_flg']);
    }
}