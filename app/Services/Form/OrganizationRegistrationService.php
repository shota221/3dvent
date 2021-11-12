<?php

namespace App\Services\Form;

use App\Exceptions;
use App\Http\Forms\Form as Form;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Repositories as Repos;
use App\Http\Response as Response;

class OrganizationRegistrationService
{
    public function create(Form\OrganizationRegistrationForm $form)
    {
        $exists_by_code = Repos\OrganizationRepository::existsByCode($form->organization_code);

        if($exists_by_code){
            $form->addError('organization_code', 'validation.duplicated_registration');
        }

        $exists_by_representative_email = Repos\OrganizationRepository::existsByRepresentativeEmail($form->representative_email);

        if($exists_by_representative_email){
            $form->addError('representative_email', 'validation.duplicated_registration');
        }

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $organization = Converter\OrganizationConverter::convertToEntity(
            $form->organization_name,
            $form->organization_code,
            $form->representative_name,
            $form->representative_email,
            $form->language_code,
        );
        
        DBUtil::Transaction(
            '未承認組織登録',
            function () use ($organization) {
                $organization->save();
            }
        );

        return new Response\SuccessJsonResult;
    }
}
