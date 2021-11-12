<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Patient;
use App\Models\PatientValue;

class PatientConverter
{
  public static function convertToPatientRegistrationResult($entity, $predicted_vt)
  {
    $res = new Response\Api\PatientResult;

    $round_at = config('calc.default.number_of_decimal_places');

    $res->patient_id = $entity->id;

    $res->predicted_vt = !empty($predicted_vt) ? strval(round($predicted_vt,$round_at)) : $predicted_vt;

    return $res;
  }

  public static function convertToPatientResult($entity, $predicted_vt)
  {
    $res = new Response\Api\PatientResult;

    $round_at = config('calc.default.number_of_decimal_places');

    $res->patient_code = strval($entity->patient_code);

    $res->height = strval($entity->height);

    $res->weight = strval($entity->weight);

    $res->gender = $entity->gender;

    $res->predicted_vt = !empty($predicted_vt) ? strval(round($predicted_vt,$round_at)) : $predicted_vt;

    return $res;
  }

  public static function convertToEntity(
    $height,
    $gender,
    $weight = null,
    $patient_code = null,
    $organization_id = null
  ) {
    $entity = new Patient;

    $entity->patient_code = $patient_code;

    $entity->height = $height;

    $entity->gender = $gender;

    $entity->weight = $weight;

    $entity->organization_id = $organization_id;

    return $entity;
  }

  public static function convertToUpdateEntity(
    Patient $entity,
    $patient_code,
    $height,
    $gender,
    $weight
  ) {
    $entity->patient_code = $patient_code;

    $entity->height = $height;

    $entity->gender = $gender;

    $entity->weight = $weight;

    return $entity;
  }

  public static function convertToPatientValueResult($patient_code, $entity)
  {
    $res = new Response\Api\PatientValueResult;

    if (is_null($entity)) {
      $res->has_observed = false;
      return $res;
    }

    $res->has_observed = true;

    $res->patient_code = $patient_code;

    $res->opt_out_flg = $entity->opt_out_flg;

    $res->age = $entity->age;

    $res->vent_disease_name = $entity->vent_disease_name;

    $res->other_disease_name_1 = $entity->other_disease_name_1;

    $res->other_disease_name_2 = $entity->other_disease_name_2;

    $res->used_place = $entity->used_place;

    $res->hospital = $entity->hospital;

    $res->national = $entity->national;

    $res->discontinuation_at = $entity->discontinuation_at;

    $res->outcome = $entity->outcome;

    $res->treatment = $entity->treatment;

    $res->adverse_event_flg = $entity->adverse_event_flg;

    $res->adverse_event_contents = $entity->adverse_event_contents;

    return $res;
  }

  public static function convertToPatientValueEntity(
    $patient_id,
    $patient_obs_user_id,
    $registered_at,
    $opt_out_flg = null,
    $age = '',
    $vent_disease_name = '',
    $other_disease_name_1 = '',
    $other_disease_name_2 = '',
    $used_place = null,
    $hospital = '',
    $national = '',
    $discontinuation_at = null,
    $outcome = null,
    $treatment = null,
    $adverse_event_flg = null,
    $adverse_event_contents = ''
  ) {
    $entity = new PatientValue;

    $entity->patient_id = $patient_id;

    $entity->patient_obs_user_id = $patient_obs_user_id;

    $entity->age = strval($age);

    $entity->vent_disease_name = strval($vent_disease_name);

    $entity->other_disease_name_1 = strval($other_disease_name_1);

    $entity->other_disease_name_2 = strval($other_disease_name_2);

    $entity->used_place = $used_place;

    $entity->hospital = strval($hospital);

    $entity->national = strval($national);

    $entity->discontinuation_at = $discontinuation_at;

    $entity->outcome = $outcome;

    $entity->treatment = $treatment;

    $entity->adverse_event_contents = $adverse_event_contents;

    if (!is_null($opt_out_flg)) {
      $entity->opt_out_flg = $opt_out_flg;
    }

    if (!is_null($adverse_event_flg)) {
      $entity->adverse_event_flg = $adverse_event_flg;
    }

    $entity->registered_at = $registered_at;

    return $entity;
  }

  // 追加　文字列のデフォルト値を空文字に変更
  public static function convertToPatientValueUpdateEntity(
    PatientValue $entity,
    $patient_obs_user_id,
    $opt_out_flg = null,
    $age = '',
    $vent_disease_name = '',
    $other_disease_name_1 = '',
    $other_disease_name_2 = '',
    $used_place = null,
    $hospital = '',
    $national = '',
    $discontinuation_at = null,
    $outcome = null,
    $treatment = null,
    $adverse_event_flg = null,
    $adverse_event_contents = ""
  ) {
    $entity->patient_obs_user_id = $patient_obs_user_id;

    $entity->age = strval($age);

    $entity->vent_disease_name = strval($vent_disease_name);

    $entity->other_disease_name_1 = strval($other_disease_name_1);

    $entity->other_disease_name_2 = strval($other_disease_name_2);

    $entity->used_place = $used_place;

    $entity->hospital = strval($hospital);

    $entity->national = strval($national);

    $entity->discontinuation_at = $discontinuation_at;

    $entity->outcome = $outcome;

    $entity->treatment = $treatment;

    $entity->adverse_event_contents = $adverse_event_contents;

    if (!is_null($opt_out_flg)) {
      $entity->opt_out_flg = $opt_out_flg;
    }

    if (!is_null($adverse_event_flg)) {
      $entity->adverse_event_flg = $adverse_event_flg;
    }

    return $entity;
  }

  public static function convertToPatientValueUpdateResult($patient_id, $patient_code)
  {
    $res = new Response\Api\PatientValueResult;

    $res->patient_id = $patient_id;

    $res->patient_code = $patient_code;

    return $res;
  }
}
