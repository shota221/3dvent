<?php

namespace App\Services\Support\Converter;

use App\Http\Response\Admin\OrganizationResult;
use App\Http\Response\Admin\UserResult;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationConverter
{
  public static function convertToEntity(
    $name,
    $code,
    $representative_name,
    $representative_email,
    $status = Organization::DISABLED,
    $patient_obs_approved_flg = Organization::PATIENT_OBS_UNAPPROVED,
    $edcid = null,
  ) {
    $entity = new Organization;

    $entity->name = $name;
    $entity->code = $code;
    $entity->representative_name = $representative_name;
    $entity->representative_email = $representative_email;
    $entity->disabled_flg = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;
    $entity->edcid = $edcid;

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
    $edcid,
  ) {

    $entity->name = $name;
    $entity->code = $code;
    $entity->representative_name = $representative_name;
    $entity->representative_email = $representative_email;
    $entity->disabled_flg = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;
    $entity->edcid = $edcid;

    return $entity;
  }

  public static function convertToPagenate(Collection $entities, $total_count, $items_per_page, $base_url)
  {
    $paginator = new LengthAwarePaginator(
      self::convertToOrganizationData($entities),
      $total_count,
      $items_per_page,
      null,
      ['path' => $base_url]
    );

    return $paginator;
  }

  public static function convertToOrganizationResult(Organization $entity)
  {
    $organization_result = new OrganizationResult;

    $organization_result->id = $entity->id;
    $organization_result->organization_name = $entity->name;
    $organization_result->organization_code = $entity->code;
    $organization_result->representative_name = $entity->representative_name;
    $organization_result->representative_email = $entity->representative_email;
    $organization_result->edc_linked_flg = $entity->edc_linked_flg;
    $organization_result->edcid = $entity->edcid;
    $organization_result->patient_obs_approved_flg = $entity->patient_obs_approved_flg;
    $organization_result->registered_at = $entity->created_at;
    $organization_result->disabled_flg = $entity->disabled_flg;

    return $organization_result;
  }

  private static function convertToOrganizationData(Collection $entities)
  {
    return array_map(
      function($entity){
        return self::convertToOrganizationResult($entity);
      },$entities->all()
    );
  }

  public static function convertToUsersListElmEntity(User $entity)
  {
    $user_result = new UserResult;

    $user_result->name = $entity->name;
    $user_result->authority = $entity->authority;
    $user_result->disabled_flg = $entity->disabled_flg;

    return $user_result;
  }
}