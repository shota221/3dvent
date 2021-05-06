<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;
use App\Services\Support\DateUtil;

class VentilatorConverter
{
    public static function convertToVentilatorResult($entity = null)
    {
        $res = new Response\Api\VentilatorResult;

        if ($res->is_registered = !is_null($entity)) {

            $res->ventilator_id = $entity->id;

            $res->patient_id = $entity->patient_id ?? null;

            $res->organization_name = $entity->organization_name ?? null;

            $res->serial_number = $entity->serial_number;

            $res->start_using_at = $entity->start_using_at;
        }

        return $res;
    }

    public static function convertToVentilatorRegistrationResult($entity)
    {
        $res = new Response\Api\VentilatorResult;

        $res->ventilator_id = $entity->id;

        $res->organization_name = $entity->organization_name ?? null;

        $res->serial_number = $entity->serial_number;

        return $res;
    }

    public static function convertToVentilatorUpdateEntity($entity,$start_using_at)
    {
        $entity->start_using_at = $start_using_at;

        return $entity;
    }

    public static function convertToVentilatorUpdateResult($entity)
    {
        $res = new Response\Api\VentilatorResult;

        $res->start_using_at = $entity->start_using_at;

        return $res;
    }

    public static function convertToVentilatorEntity($gs1_code, $serial_number, $latitude = null, $longitude = null, $city = null, $organization_id = null, $registered_user_id = null)
    {
        $entity = new Ventilator;

        $entity->gs1_code = $gs1_code;

        $entity->serial_number = $serial_number;

        if (!is_null($latitude) && !is_null($longitude)) {
            $entity->location = ['lat' => $latitude, 'lng' => $longitude];
        }

        $entity->city = $city;

        $entity->organization_id = $organization_id;

        $entity->registered_user_id = $registered_user_id;

        $entity->start_using_at = DateUtil::now();

        return $entity;
    }
}
