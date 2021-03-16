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
}
