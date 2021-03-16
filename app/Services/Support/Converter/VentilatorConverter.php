<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;

class VentilatorConverter
{
    public static function convertToVentilatorResult($entity = null)
    {
        $res = new Response\Api\VentilatorResult;

        if ($res->is_registered = !is_null($entity)) {

            $res->ventilator_id = $entity->id;

            $res->patient_id = $entity->patient_id ?? null;

            $res->organization_name = $entity->organization_name ?? null;
        }

        return $res;
    }

    public static function convertToDefaultFlowResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->air_flow =  '9.0';

        $res->o2_flow =  '3.0';

        return $res;
    }

    public static function convertToEstimatedDataResult($estimated_peep = null, $fio2 = null)
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->estimated_peep = !is_null($estimated_peep) ? strval($estimated_peep) : null;

        $res->fio2 = !is_null($fio2) ? strval($fio2) : null;

        return $res;
    }

    public static function convertToVentilatorRegistrationResult($entity)
    {
        $res = new Response\Api\VentilatorResult;

        $res->ventilator_id = $entity->id;

        $res->organization_name = $entity->organization_name ?? null;

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult($entity)
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->ventilator_id = $entity->ventilator_id;

        $res->estimated_vt = strval($entity->estimated_vt);

        $res->estimated_mv = strval($entity->estimated_mv);

        $res->estimated_peep = strval($entity->estimated_peep);

        $res->fio2 = strval($entity->fio2);

        return $res;
    }

    public static function convertToVentilatorValueResult($entity)
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->patient_id = $entity->patient_id;

        $res->airway_pressure = strval($entity->airway_pressure);

        $res->air_flow = strval($entity->air_flow);

        $res->o2_flow = strval($entity->o2_flow);

        $res->rr = strval($entity->rr);

        $res->estimated_vt = strval($entity->estimated_vt);

        $res->estimated_mv = strval($entity->estimated_mv);

        $res->estimated_peep = strval($entity->estimated_peep);

        $res->fio2 = strval($entity->fio2);

        return $res;
    }

    public static function convertToVentilatorValueUpdateResult($entity = null)
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->fixed_flg = !is_null($entity) ? $entity->fixed_flg : 0;

        return $res;
    }

    public static function convertToVentilatorEntity($gs1_code, $latitude, $longitude, $organization_id, $registered_user_id)
    {
        $entity = new Ventilator;

        $entity->gs1_code = $gs1_code;

        if (!is_null($latitude) && !is_null($longitude)) {
            $entity->location = ['lat' => $latitude, 'lng' => $longitude];
        }

        $entity->organization_id = $organization_id ?? null;

        $entity->registered_user_id = $registered_user_id ?? null;

        return $entity;
    }

    public static function convertToVentilatorValueEntity(
        Patient $patient,
        $ventilator_id,
        $airway_pressure,
        $air_flow,
        $o2_flow,
        $rr,
        $i_avg,
        $e_avg,
        $vt_per_kg,
        $predicted_vt,
        $estimated_vt,
        $estimated_mv,
        $estimated_peep,
        $fio2,
        $total_flow,
        $user_id,
        $appkey_id
    ) {
        $entity = new VentilatorValue;

        $entity->ventilator_id = $ventilator_id;

        $entity->height = $patient->height;

        $entity->gender = $patient->gender;

        $entity->airway_pressure = $airway_pressure;

        $entity->air_flow = $air_flow;

        $entity->o2_flow = $o2_flow;

        $entity->rr = $rr;

        $entity->inspiratory_time = $i_avg;

        $entity->expiratory_time = $e_avg;

        $entity->vt_per_kg = $vt_per_kg ?? 6;

        $entity->predicted_vt = $predicted_vt;

        $entity->estimated_vt = $estimated_vt;

        $entity->estimated_mv = $estimated_mv;

        $entity->estimated_peep = $estimated_peep;

        $entity->fio2 = $fio2;

        $entity->total_flow = $total_flow;

        $entity->user_id = $user_id ?? null;

        $entity->appkey_id = $appkey_id;

        return $entity;
    }

    public static function convertToVentilatorValueUpdateEntity(VentilatorValue $entity, $fixed_flg, $fixed_at)
    {
        $entity->fixed_flg = $fixed_flg;

        $entity->fixed_at = $fixed_at;

        //確認用インターフェースができるまで、最終設定フラグが立ったものは確認済みとみなす。
        $entity->confirmed_flg = $fixed_flg;
        $entity->confirmed_at = $fixed_at;

        return $entity;
    }
}
