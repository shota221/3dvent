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

        $res->predicted_vt = $predicted_vt;

        return $res;
    }

    public static function convertToPatientResult($entity, $predicted_vt)
    {
        $res = new Response\Api\PatientResult;

        $res->nickname = $entity->nickname;

        $res->height = $entity->height;

        $res->gender = $entity->gender;

        $res->other_attrs =$entity->other_attrs;

        $res->predicted_vt = $predicted_vt;

        return $res;
    }

    public static function convertToEntity(Form\PatientCreateForm $form)
    {
        $entity = new Patient;

        $entity->nickname = $form->nickname;

        $entity->height = $form->height;

        $entity->gender = $form->gender;

        $entity->ideal_weight = $form->ideal_weight;

        $entity->other_attrs = $form->other_attrs;

        return $entity;
    }

    public static function convertToUpdateEntity(Form\PatientUpdateForm $form, Patient $entity)
    {
        $entity->nickname = $form->nickname;

        $entity->height = $form->height;

        $entity->gender = $form->gender;

        $entity->other_attrs = $form->other_attrs;

        return $entity;
    }
}
