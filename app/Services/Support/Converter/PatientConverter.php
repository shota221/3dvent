<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Patient;

class PatientConverter
{
    public static function convertToPatientRegistrationResult($entity, $predicted_vt)
    {
        $res = new Response\Api\PatientResult;

        $res->patient_id = $entity->id;

        $res->predicted_vt = strval($predicted_vt);

        return $res;
    }

    public static function convertToPatientResult($entity, $predicted_vt)
    {
        $res = new Response\Api\PatientResult;

        $res->nickname = $entity->nickname;

        $res->height = strval($entity->height);

        $res->gender = strval($entity->gender);

        $res->other_attrs = $entity->other_attrs;

        $res->predicted_vt = strval($predicted_vt);

        return $res;
    }

    public static function convertToEntity(
        $nickname,
        $height,
        $gender,
        $ideal_weight,
        $other_attrs
    ) {
        $entity = new Patient;

        $entity->nickname = $nickname;

        $entity->height = $height;

        $entity->gender = $gender;

        $entity->ideal_weight = $ideal_weight;

        $entity->other_attrs = $other_attrs;

        return $entity;
    }

    public static function convertToUpdateEntity(
        Patient $entity,
        $nickname,
        $height,
        $gender,
        $other_attrs
    ) {
        $entity->nickname = $nickname;

        $entity->height = $height;

        $entity->gender = $gender;

        $entity->other_attrs = $other_attrs;

        return $entity;
    }

    //TODO 以下補完作業
    public static function convertToPatientValueResult()
    {
        return <<<EOF
        {
            "result": {
              "has_observed": true,
              "observed_at": "2021-02-08 12:34:06",
              "opt_out_flg": 1,
              "patient_code": "999",
              "age": "21",
              "vent_disease_name": "XXXXXX",
              "other_disease_name_1": "xXXXXX",
              "other_disease_name_2": "XXXXXX",
              "userd_place": 3,
              "hospital_name": "XXXXXX",
              "national_name": "XXXXXX",
              "discontinuation_at": "2021-04-08 17:04:01",
              "outcome": 1,
              "treatment": 1,
              "adverse_event_flg": 1,
              "adverse_event_contents": "XXXXXX"
            }
          }
        EOF;
    }

    public static function convertToPatientValueRegistrationResult()
    {
        return <<<EOF
        {
            "result": {
              "observed_at": "2021-02-08 12:34:06",
              "patient_code": "999"
            }
          }
        EOF;
    }

    public static function convertToPatientValueUpdateResult()
    {
        return <<<EOF
        {
            "result": {
              "observed_at": "2021-02-08 12:34:06",
              "patient_code": "999"
            }
          }
        EOF;
    }
}
