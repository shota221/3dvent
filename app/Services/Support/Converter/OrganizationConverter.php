<?php

namespace App\Services\Support\Converter;

use App\Http\Response\Admin\OrganizationData;
use App\Http\Response\Admin\OrganizationResult;
use App\Http\Response\Admin\UserResult;
use App\Services\Support\Converter;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationConverter
{
  public static function convertToEntity(
    string $name,
    string $code,
    string $representative_name,
    string $representative_email,
    string $language_code,
    int    $status = Organization::DISABLED,
    int    $patient_obs_approved_flg = Organization::PATIENT_OBS_UNAPPROVED,
    string $edcid = null
  ) {
    $entity = new Organization;

    $entity->name                     = $name;
    $entity->code                     = $code;
    $entity->representative_name      = $representative_name;
    $entity->representative_email     = $representative_email;
    $entity->disabled_flg             = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;
    $entity->locale                   = $language_code;
    $entity->edcid                    = $edcid;

    return $entity;
  }

  public static function convertToUpdateEntity(
    Organization $entity,
    string $name,
    string $code,
    string $representative_name,
    string $representative_email,
    int    $status,
    int    $patient_obs_approved_flg,
    string $edcid = null,
    string $language_code
  ) {

    $entity->name                     = $name;
    $entity->code                     = $code;
    $entity->representative_name      = $representative_name;
    $entity->representative_email     = $representative_email;
    $entity->disabled_flg             = $status;
    $entity->patient_obs_approved_flg = $patient_obs_approved_flg;
    $entity->edcid                    = $edcid;
    $entity->locale                   = $language_code;

    return $entity;
  }

  public static function convertToPaginate(Collection $entities, $total_count, $items_per_page, $base_url)
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

    $organization_result->id                       = $entity->id;
    $organization_result->organization_name        = $entity->name;
    $organization_result->organization_code        = $entity->code;
    $organization_result->representative_name      = $entity->representative_name;
    $organization_result->representative_email     = $entity->representative_email;
    $organization_result->edc_linked_flg           = $entity->edc_linked_flg;
    $organization_result->edcid                    = $entity->edcid;
    $organization_result->patient_obs_approved_flg = $entity->patient_obs_approved_flg;
    $organization_result->registered_at            = $entity->created_at;
    $organization_result->disabled_flg             = $entity->disabled_flg;
    $organization_result->language_code            = $entity->locale;

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

  private static function convertToUsersListElm(User $entity)
  {
    $user_result = new UserResult;

    $user_result->name = $entity->name;
    // $user_result->authority = $entity->authority;
    
    if ($entity->admin_flg) {
      $user_result->authority = Converter\Lang\Authority::convertToAdminAuthorityName($entity->admin_authority_type);
    } else {
      $user_result->authority = Converter\Lang\Authority::convertToOrgAuthorityName($entity->org_authority_type);
    }
    
    $user_result->disabled_flg = $entity->disabled_flg;

    return $user_result;
  }

  public static function convertToUsersList(Collection $entities)
  {
    return array_map(
      function($entity){
        return self::convertToUsersListElm($entity);
      },$entities->all()
    );
  }
  
  public static function convertToOrganizationSearchList(Collection $entities)
  {
      $organizations = array_map(
          function($entity) {
              return self::convertToOrganizationSearchListElm($entity);
          }
          ,$entities->all()
      );

      return $organizations;
  }

  private static function convertToOrganizationSearchListElm(Organization $entity)
  {
      $data = new OrganizationData();

      $data->id = $entity->id;
      $data->name = $entity->name;

      return $data;
  }
}