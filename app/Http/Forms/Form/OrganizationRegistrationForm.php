<?php

namespace App\Http\Forms\Form;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class OrganizationRegistrationForm extends BaseForm
{
    public $organization_name;
    public $representative_name;
    public $representative_email;
    public $organization_code;
    public $language_code;
    
    protected function validationRule()
    {
        return [
            'organization_name'    => 'required|' . Rule::VALUE_NAME,
            'representative_name'  => 'required|' . Rule::VALUE_NAME,
            'representative_email' => 'required|' . Rule::EMAIL,
            'organization_code'    => 'required|' . Rule::VALUE_CODE,
            'language_code'        => 'required|' . Rule::valueLanguageCode(),
        ]; 
    }

    protected function bind($input)
    {
        $this->organization_name    = strval($input['organization_name']);
        $this->representative_name  = strval($input['representative_name']);
        $this->representative_email = strval($input['representative_email']);
        $this->organization_code    = strval($input['organization_code']);
        $this->language_code        = strval($input['language_code']);
    }
}