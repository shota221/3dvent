<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Http\Response\Api\VentilatorResult;
use App\Models;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;
use App\Models\VentilatorBug;
use App\Services\Support\DateUtil;
use App\Services\Support\Gs1Util;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class VentilatorConverter
{
  public static function convertToVentilatorResult($entity = null, $is_recommended_period = null)
  {
    $res = new Response\Api\VentilatorResult;

    $isRegisteredVentilator = !is_null($entity);

    $res->is_registered = $isRegisteredVentilator;

    if ($isRegisteredVentilator) {

      $res->organization_name = $entity->organization_name;

      $res->organization_code = $entity->organization_code;

      $res->ventilator_id = $entity->id;

      $res->patient_id = $entity->patient_id;

      $res->serial_number = strval($entity->serial_number);

      $res->start_using_at = $entity->start_using_at;

      $res->is_recommended_period = $is_recommended_period;
    }

    $res->units = config('units');

    return $res;
  }

  public static function convertToVentilatorRegistrationResult($entity)
  {
    $res = new Response\Api\VentilatorResult;

    $res->ventilator_id = $entity->id;

    $res->organization_name = $entity->organization_name;

    $res->organization_code = $entity->organization_code;

    $res->serial_number = strval($entity->serial_number);

    return $res;
  }

  public static function convertToVentilatorUpdateEntity($entity, $organization_id, $start_using_at = null)
  {
    $entity->organization_id = $organization_id;

    if (!is_null($start_using_at)) {
      $entity->start_using_at = $start_using_at;
    }

    return $entity;
  }

  public static function convertToVentilatorUpdateResult($entity)
  {
    $res = new Response\Api\VentilatorResult;

    $res->start_using_at = $entity->start_using_at;

    return $res;
  }

  public static function convertToVentilatorEntity($gs1_code, $serial_number, $expiration_date, $qr_read_at, $latitude = null, $longitude = null, $city = null, $organization_id = null, $registered_user_id = null)
  {
    $entity = new Ventilator;

    $entity->gs1_code = $gs1_code;

    $entity->serial_number = $serial_number;

    $entity->expiration_date = $expiration_date;

    if (!is_null($latitude) && !is_null($longitude)) {
      $entity->location = ['lat' => $latitude, 'lng' => $longitude];
    }

    $entity->city = $city;

    $entity->organization_id = $organization_id;

    $entity->registered_user_id = $registered_user_id;

    $entity->start_using_at = $qr_read_at;

    $entity->qr_read_at = $qr_read_at;

    return $entity;
  }


  public static function convertToAdminPaginate(\Illuminate\Database\Eloquent\Collection $entities, $total_count, $items_per_page, $base_url)
  {
    $paginator = new LengthAwarePaginator(
      self::convertToAdminVentilatorData($entities),
      $total_count,
      $items_per_page,
      null,
      ['path' => $base_url]
    );

    return $paginator;
  }

  public static function convertToAdminVentilatorResult(Models\Ventilator $entity)
  {
    $ventilator_result = new Response\Admin\VentilatorResult;

    $ventilator_result->id = $entity->id;
    $ventilator_result->gs1_code = $entity->gs1_code;
    $ventilator_result->serial_number = $entity->serial_number;
    $ventilator_result->organization_name = $entity->organization_name;
    $ventilator_result->registered_user_name = $entity->registered_user_name;
    $ventilator_result->expiration_date = $entity->expiration_date;
    $ventilator_result->start_using_at = $entity->start_using_at;
    $ventilator_result->has_bug = !is_null($entity->bug_ventialtor_id);

    return $ventilator_result;
  }

  private static function convertToAdminVentilatorData(\Illuminate\Database\Eloquent\Collection $entities)
  {
    return array_map(
      function ($entity) {
        return self::convertToAdminVentilatorResult($entity);
      },
      $entities->all()
    );
  }

  public static function convertToPatientResult($patient_code)
  {
    $patient_result = new Response\Admin\PatientResult;

    $patient_result->patient_code = $patient_code;

    return $patient_result;
  }

  public static function convertToAdminVentilatorUpdateEntity($entity, $start_using_at)
  {
    $entity->start_using_at = $start_using_at;

    return $entity;
  }

  public static function convertToBugListElmEntity(VentilatorBug $entity)
  {
    $bug_result = new Response\Admin\VentilatorBugResult;

    $bug_result->bug_name = $entity->bug_name;
    $bug_result->request_improvement = $entity->request_improvement;
    $bug_result->registered_at = $entity->registered_at;
    $bug_result->registered_user_name = $entity->registered_user_name;

    return $bug_result;
  }

  public static function convertToBugListData(\Illuminate\Database\Eloquent\Collection $entities)
  {
    return array_map(
      function ($entity) {
        return self::convertToBugListElmEntity($entity);
      },
      $entities->all()
    );
  }


  public static function convertToImportedVentilatorEntity($entity, $organization_id, $registered_user_id, $gs1_code, $serial_number, $city, $qr_read_at, $expiration_date, $start_using_at)
  {
    $entity->organization_id = $organization_id;
    $entity->registered_user_id = $registered_user_id;
    $entity->gs1_code = $gs1_code;
    $entity->serial_number = $serial_number;
    $entity->city = $city;
    $entity->qr_read_at = $qr_read_at;
    $entity->expiration_date = $expiration_date;
    $entity->start_using_at = $start_using_at;

    return $entity;
  }

  
  public static function convertToOrgPaginate(\Illuminate\Database\Eloquent\Collection $entities, $total_count, $items_per_page, $base_url)
  {
    $paginator = new LengthAwarePaginator(
      self::convertToOrgVentilatorData($entities),
      $total_count,
      $items_per_page,
      null,
      ['path' => $base_url]
    );

    return $paginator;
  }

  public static function convertToOrgVentilatorResult(Models\Ventilator $entity)
  {
    $ventilator_result = new Response\Org\VentilatorResult;

    $ventilator_result->id = $entity->id;
    $ventilator_result->gs1_code = $entity->gs1_code;
    $ventilator_result->serial_number = $entity->serial_number;
    $ventilator_result->registered_user_name = $entity->registered_user_name;
    $ventilator_result->expiration_date = $entity->expiration_date;
    $ventilator_result->start_using_at = $entity->start_using_at;
    $ventilator_result->has_bug = !is_null($entity->bug_ventialtor_id);

    return $ventilator_result;
  }

  private static function convertToOrgVentilatorData(\Illuminate\Database\Eloquent\Collection $entities)
  {
    return array_map(
      function ($entity) {
        return self::convertToOrgVentilatorResult($entity);
      },
      $entities->all()
    );
  }
}
