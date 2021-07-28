<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Org as Form;;
use App\Http\Response as Response;
use App\Models;

class OrganizationSettingConverter
{
    public static function convertToSettingResult($entity)
    {
        $response = new Response\Org\OrganizationSettingResult;

        $response->ventilator_value_scan_interval = $entity->ventilator_value_scan_interval;
        $response->vt_per_kg = $entity->vt_per_kg;

        return $response;
    }

    public static function convertToUpdateEntity(
        Models\OrganizationSetting $entity, 
        Form\OrganizationSettingUpdateForm $form
    ) {
        $entity->ventilator_value_scan_interval = $form->ventilator_value_scan_interval;
        $entity->vt_per_kg = $form->vt_per_kg;

        return $entity;
    }
}