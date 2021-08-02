<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\Organization;

class OrganizationConverter
{
  public static function convertToEntity(
    $name,
    $code,
    $representative_name,
    $representative_email,
    $status = Organization::DISABLED,
    $patient_obs_approved_flg = Organization::PARIENT_OBS_UNAPPROVED,
  ) {
    $entity = new Organization;

    $entity->name = $name;
    $entity->code = $code;
    $entity->representative_name = $representative_name;
    $entity->representative_email = $representative_email;
    $entity->disabled_flg = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;

    return $entity;
  }

  public static function convertToUpdateEntity(
    Organization $entity,
    $name,
    $code,
    $representative_name,
    $representative_email,
    $status,
    $patient_obs_approved_flg,
  ) {

    $entity->name = $name;
    $entity->code = $code;
    $entity->representative_name = $representative_name;
    $entity->representative_email = $representative_email;
    $entity->disabled_flg = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;

    return $entity;
  }
}