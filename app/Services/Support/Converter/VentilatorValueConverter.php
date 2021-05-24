<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;
use App\Models\VentilatorValueHistory;
use App\Services\Support\DateUtil;

class VentilatorValueConverter
{
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

    public static function convertToVentilatorValueResult($entity, $registered_user_name = null)
    {
        $res = new Response\Api\VentilatorValueResult;

        if (is_null($entity)) {
            $res->has_observed = false;
            return $res;
        }

        $res->has_observed = true;

        $res->ventilator_value_id = $entity->id;

        $res->registered_at = $entity->registered_at;

        $res->registered_user_name = $registered_user_name;

        $res->city = strval($entity->city);

        $res->gender = $entity->gender;

        $res->height = strval($entity->height);

        $res->weight = strval($entity->weight);

        $res->airway_pressure = strval($entity->airway_pressure);

        $res->total_flow = strval($entity->total_flow);

        $res->air_flow = strval($entity->air_flow);

        $res->o2_flow = strval($entity->o2_flow);

        $res->rr = strval($entity->rr);

        $res->expiratory_time = strval($entity->expiratory_time);

        $res->inspiratory_time = strval($entity->inspiratory_time);

        $res->vt_per_kg = strval($entity->vt_per_kg);

        $res->predicted_vt = strval($entity->predicted_vt);

        $res->estimated_vt = strval($entity->estimated_vt);

        $res->estimated_mv = strval($entity->estimated_mv);

        $res->estimated_peep = strval($entity->estimated_peep);

        $res->fio2 = strval($entity->fio2);

        $res->status_use = $entity->status_use;

        $res->status_use_other = strval($entity->status_use_other);

        $res->spo2 = strval($entity->spo2);

        $res->etco2 = strval($entity->etco2);

        $res->pao2 = strval($entity->pao2);

        $res->paco2 = strval($entity->paco2);

        return $res;
    }

    public static function convertToVentilatorValueEntity(
        $ventilator_id,
        $height,
        $gender,
        $ideal_weight,
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
        $registered_at,
        $appkey_id,
        $user_id = null
    ) {
        $entity = new VentilatorValue;

        $entity->ventilator_id = $ventilator_id;

        $entity->height = strval($height);

        $entity->gender = $gender;

        $entity->ideal_weight = strval($ideal_weight);

        $entity->airway_pressure = strval($airway_pressure);

        $entity->air_flow = strval($air_flow);

        $entity->o2_flow = strval($o2_flow);

        $entity->rr = strval($rr);

        $entity->inspiratory_time = strval($i_avg);

        $entity->expiratory_time = strval($e_avg);

        $entity->vt_per_kg = strval($vt_per_kg);

        $entity->predicted_vt = strval($predicted_vt);

        $entity->estimated_vt = strval($estimated_vt);

        $entity->estimated_mv = strval($estimated_mv);

        $entity->estimated_peep = strval($estimated_peep);

        $entity->fio2 = strval($fio2);

        $entity->total_flow = strval($total_flow);

        $entity->registered_at = $registered_at;

        $entity->appkey_id = $appkey_id;

        $entity->registered_user_id = $user_id;

        return $entity;
    }

    public static function convertToVentilatorValueUpdateEntity(
        VentilatorValue $entity,
        $height,
        $gender,
        $ideal_weight,
        $airway_pressure,
        $air_flow,
        $o2_flow,
        $vt_per_kg,
        $predicted_vt,
        $estimated_vt,
        $estimated_mv,
        $estimated_peep,
        $fio2,
        $total_flow,
        $weight = '',
        $status_use = null,
        $status_use_other = '',
        $spo2 = '',
        $etco2 = '',
        $pao2 = '',
        $paco2 = ''
    ) {
        $entity->height = strval($height);

        $entity->gender = $gender;

        $entity->ideal_weight = strval($ideal_weight);

        $entity->airway_pressure = strval($airway_pressure);

        $entity->air_flow = strval($air_flow);

        $entity->o2_flow = strval($o2_flow);

        $entity->vt_per_kg = strval($vt_per_kg);

        $entity->predicted_vt = strval($predicted_vt);

        $entity->estimated_vt = strval($estimated_vt);

        $entity->estimated_mv = strval($estimated_mv);

        $entity->estimated_peep = strval($estimated_peep);

        $entity->fio2 = strval($fio2);

        $entity->total_flow = strval($total_flow);

        $entity->weight = strval($weight);

        $entity->status_use = $status_use;

        $entity->status_use_other = strval($status_use_other);

        $entity->spo2 = strval($spo2);

        $entity->etco2 = strval($etco2);

        $entity->pao2 = strval($pao2);

        $entity->paco2 = strval($paco2);

        return $entity;
    }

    public static function convertToVentilatorValueListElm($id, $registered_at, $registered_user_name = null)
    {
        $res = new Response\Api\VentilatorValueElm;

        $res->id = $id;

        $res->registered_at = $registered_at;

        $res->registered_user_name = $registered_user_name;

        return $res;
    }

    public static function convertToVentilatorValueUpdateResult($id, $revised_at)
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->id = $id;

        $res->revised_at = $revised_at;

        return $res;
    }
}
