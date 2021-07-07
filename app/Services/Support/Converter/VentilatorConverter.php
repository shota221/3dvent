<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;
use App\Services\Support\DateUtil;
use Carbon\Carbon;

class VentilatorConverter
{
    public static function convertToVentilatorResult($entity = null)
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

            $from = DateUtil::parseToDatetime($entity->start_using_at);

            $to = DateUtil::hourLater($from,config('calc.default.recommended_period_hour'));

            $res->is_recommended_period = DateUtil::isBetweenDateTimeToAnother(DateUtil::now(),$from,$to);
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

    public static function convertToVentilatorEntity($gs1_code, $serial_number, $qr_read_at, $latitude = null, $longitude = null, $city = null, $organization_id = null, $registered_user_id = null)
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

        $entity->start_using_at = $qr_read_at;

        $entity->qr_read_at = $qr_read_at;

        return $entity;
    }
}
