<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;
use App\Models\VentilatorValue;
use App\Models\Patient;
use App\Models\VentilatorValueHistory;
use App\Services\Support\DateUtil;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $round_at = config('calc.default.number_of_decimal_places');

        $res->estimated_peep = !is_null($estimated_peep) ? strval(round($estimated_peep, $round_at)) : null;

        $res->fio2 = !is_null($fio2) ? strval(round($fio2, $round_at)) : null;

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult($entity)
    {
        $res = new Response\Api\VentilatorValueResult;

        $round_at = config('calc.default.number_of_decimal_places');

        $res->ventilator_id = $entity->ventilator_id;

        $res->estimated_vt = !empty($entity->estimated_vt) ? strval(round($entity->estimated_vt, $round_at)) : $entity->estimated_vt;

        $res->estimated_mv = !empty($entity->estimated_mv) ? strval(round($entity->estimated_mv, $round_at)) : $entity->estimated_mv;

        $res->estimated_peep = !empty($entity->estimated_peep) ? strval(round($entity->estimated_peep, $round_at)) : $entity->estimated_peep;

        $res->fio2 = !empty($entity->fio2) ? strval(round($entity->fio2, $round_at)) : $entity->fio2;

        return $res;
    }

    public static function convertToVentilatorValueResult($entity, $registered_user_name = null)
    {
        $res = new Response\Api\VentilatorValueResult;

        if (is_null($entity)) {
            $res->has_observed = false;
            return $res;
        }

        $round_at = config('calc.default.number_of_decimal_places');

        $res->has_observed = true;

        $res->ventilator_value_id = $entity->id;

        $res->registered_at = $entity->registered_at;

        $res->registered_user_name = $registered_user_name;

        $res->city = strval($entity->city);

        $res->gender = $entity->gender;

        $res->height = !empty($entity->height) ? strval(round($entity->height, $round_at)) : $entity->height;

        $res->weight = !empty($entity->weight) ? strval(round($entity->weight, $round_at)) : $entity->weight;

        $res->airway_pressure = !empty($entity->airway_pressure) ? strval(round($entity->airway_pressure, $round_at)) : $entity->airway_pressure;

        $res->total_flow = !empty($entity->total_flow) ? strval(round($entity->total_flow, $round_at)) : $entity->total_flow;

        $res->air_flow = !empty($entity->air_flow) ? strval(round($entity->air_flow, $round_at)) : $entity->air_flow;

        $res->o2_flow = !empty($entity->o2_flow) ? strval(round($entity->o2_flow, $round_at)) : $entity->o2_flow;

        $res->rr = !empty($entity->rr) ? strval(round($entity->rr, $round_at)) : $entity->rr;

        $res->expiratory_time = !empty($entity->expiratory_time) ? strval(round($entity->expiratory_time, $round_at)) : $entity->expiratory_time;

        $res->inspiratory_time = !empty($entity->inspiratory_time) ? strval(round($entity->inspiratory_time, $round_at)) : $entity->inspiratory_time;

        $res->vt_per_kg = !empty($entity->vt_per_kg) ? strval(round($entity->vt_per_kg, $round_at)) : $entity->vt_per_kg;

        $res->predicted_vt = !empty($entity->predicted_vt) ? strval(round($entity->predicted_vt, $round_at)) : $entity->predicted_vt;

        $res->estimated_vt = !empty($entity->estimated_vt) ? strval(round($entity->estimated_vt, $round_at)) : $entity->estimated_vt;

        $res->estimated_mv = !empty($entity->estimated_mv) ? strval(round($entity->estimated_mv, $round_at)) : $entity->estimated_mv;

        $res->estimated_peep = !empty($entity->estimated_peep) ? strval(round($entity->estimated_peep, $round_at)) : $entity->estimated_peep;

        $res->fio2 = !empty($entity->fio2) ? strval(round($entity->fio2, $round_at)) : $entity->fio2;

        $res->status_use = $entity->status_use;

        $res->status_use_other = strval($entity->status_use_other);

        $res->spo2 = !empty($entity->spo2) ? strval(round($entity->spo2, $round_at)) : $entity->spo2;

        $res->etco2 = !empty($entity->etco2) ? strval(round($entity->etco2, $round_at)) : $entity->etco2;

        $res->pao2 = !empty($entity->pao2) ? strval(round($entity->pao2, $round_at)) : $entity->pao2;

        $res->paco2 = !empty($entity->paco2) ? strval(round($entity->paco2, $round_at)) : $entity->paco2;

        return $res;
    }

    public static function convertToVentilatorValueEntity(
        $ventilator_id,
        $height,
        $weight,
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
        $user_id = null,
        $status_use = null,
        $status_use_other = '',
        $spo2 = '',
        $etco2 = '',
        $pao2 = '',
        $paco2 = '',
        $fixed_flg = null,
        $fixed_at = null,
        $confirmed_flg = null,
        $confirmed_at = null,
        $confirmed_user_id = null
    ) {
        $entity = new VentilatorValue;

        $round_at = config('calc.default.number_of_decimal_places');

        $entity->ventilator_id = $ventilator_id;

        $entity->height = !empty($height) ? strval(round($height, $round_at)) : $height;

        $entity->weight = !empty($weight) ? strval(round($weight, $round_at)) : $weight;

        $entity->gender = $gender;

        $entity->ideal_weight = !empty($ideal_weight) ? strval(round($ideal_weight, $round_at)) : $ideal_weight;

        $entity->airway_pressure = !empty($airway_pressure) ? strval(round($airway_pressure, $round_at)) : $airway_pressure;

        $entity->air_flow = !empty($air_flow) ? strval(round($air_flow, $round_at)) : $air_flow;

        $entity->o2_flow = !empty($o2_flow) ? strval(round($o2_flow, $round_at)) : $o2_flow;

        $entity->rr = !empty($rr) ? strval(round($rr, $round_at)) : $rr;

        $entity->inspiratory_time = !empty($i_avg) ? strval(round($i_avg, $round_at)) : $i_avg;

        $entity->expiratory_time = !empty($e_avg) ? strval(round($e_avg, $round_at)) : $e_avg;

        $entity->vt_per_kg = !empty($vt_per_kg) ? strval(round($vt_per_kg, $round_at)) : $vt_per_kg;

        $entity->predicted_vt = !empty($predicted_vt) ? strval(round($predicted_vt, $round_at)) : $predicted_vt;

        $entity->estimated_vt = !empty($estimated_vt) ? strval(round($estimated_vt, $round_at)) : $estimated_vt;

        $entity->estimated_mv = !empty($estimated_mv) ? strval(round($estimated_mv, $round_at)) : $estimated_mv;

        $entity->estimated_peep = !empty($estimated_peep) ? strval(round($estimated_peep, $round_at)) : $estimated_peep;

        $entity->fio2 = !empty($fio2) ? strval(round($fio2, $round_at)) : $fio2;

        $entity->total_flow = !empty($total_flow) ? strval(round($total_flow, $round_at)) : $total_flow;

        $entity->registered_at = $registered_at;

        $entity->appkey_id = $appkey_id;

        $entity->registered_user_id = $user_id;

        $entity->status_use = $status_use;

        $entity->status_use_other = $status_use_other;

        $entity->spo2 = $spo2;

        $entity->etco2 = $etco2;

        $entity->pao2 = $pao2;

        $entity->paco2 = $paco2;

        if (!is_null($fixed_flg)) {
            $entity->fixed_flg = $fixed_flg;
            $entity->fixed_at = $fixed_at;
        }

        if (!is_null($confirmed_flg)) {
            $entity->confirmed_flg = $confirmed_flg;
            $entity->confirmed_at = $confirmed_at;
            $entity->confirmed_user_id = $confirmed_user_id;
        }

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
        $round_at = config('calc.default.number_of_decimal_places');

        $entity->height = !empty($height) ? strval(round($height, $round_at)) : $height;

        $entity->gender = $gender;

        $entity->ideal_weight = !empty($ideal_weight) ? strval(round($ideal_weight, $round_at)) : $ideal_weight;

        $entity->airway_pressure = !empty($airway_pressure) ? strval(round($airway_pressure, $round_at)) : $airway_pressure;

        $entity->air_flow = !empty($air_flow) ? strval(round($air_flow, $round_at)) : $air_flow;

        $entity->o2_flow = !empty($o2_flow) ? strval(round($o2_flow, $round_at)) : $o2_flow;

        $entity->vt_per_kg = !empty($vt_per_kg) ? strval(round($vt_per_kg, $round_at)) : $vt_per_kg;

        $entity->predicted_vt = !empty($predicted_vt) ? strval(round($predicted_vt, $round_at)) : $predicted_vt;

        $entity->estimated_vt = !empty($estimated_vt) ? strval(round($estimated_vt, $round_at)) : $estimated_vt;

        $entity->estimated_mv = !empty($estimated_mv) ? strval(round($estimated_mv, $round_at)) : $estimated_mv;

        $entity->estimated_peep = !empty($estimated_peep) ? strval(round($estimated_peep, $round_at)) : $estimated_peep;

        $entity->fio2 = !empty($fio2) ? strval(round($fio2, $round_at)) : $fio2;

        $entity->total_flow = !empty($total_flow) ? strval(round($total_flow, $round_at)) : $total_flow;

        $entity->weight = !empty($weight) ? strval(round($weight, $round_at)) : $weight;

        $entity->status_use = $status_use;

        $entity->status_use_other = strval($status_use_other);

        $entity->spo2 = !empty($spo2) ? strval(round($spo2, $round_at)) : $spo2;

        $entity->etco2 = !empty($etco2) ? strval(round($etco2, $round_at)) : $etco2;

        $entity->pao2 = !empty($pao2) ? strval(round($pao2, $round_at)) : $pao2;

        $entity->paco2 = !empty($paco2) ? strval(round($paco2, $round_at)) : $paco2;

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

    public static function convertToAdminPagenate(Collection $entities, $total_count, $items_per_page, $base_url)
    {
        $paginator = new LengthAwarePaginator(
            self::convertToAdminVentilatorValueData($entities),
            $total_count,
            $items_per_page,
            null,
            ['path' => $base_url]
        );

        return $paginator;
    }

    public static function convertToAdminVentilatorValueResult(VentilatorValue $entity)
    {
        $ventilator_value_result = new Response\Admin\VentilatorValueResult;

        $ventilator_value_result->id = $entity->id;
        $ventilator_value_result->patient_code = $entity->patient_code;
        $ventilator_value_result->gs1_code = $entity->gs1_code;
        $ventilator_value_result->organization_name = $entity->organization_name;
        $ventilator_value_result->registered_user_name = $entity->registered_user_name;
        $ventilator_value_result->registered_at = $entity->registered_at;
        $ventilator_value_result->updated_at = $entity->created_at;
        $ventilator_value_result->fixed_flg = $entity->fixed_flg;
        $ventilator_value_result->confirmed_flg = $entity->confirmed_flg;
        $ventilator_value_result->height = $entity->height;
        $ventilator_value_result->weight = $entity->weight;
        $ventilator_value_result->gender = Lang\VentilatorValue::convertToGenderName($entity->gender);
        $ventilator_value_result->airway_pressure = $entity->airway_pressure;
        $ventilator_value_result->air_flow = $entity->air_flow;
        $ventilator_value_result->o2_flow = $entity->o2_flow;
        $ventilator_value_result->fio2 = $entity->fio2;
        $ventilator_value_result->rr = $entity->rr;
        $ventilator_value_result->estimated_vt = $entity->estimated_vt;
        $ventilator_value_result->estimated_mv = $entity->estimated_mv;
        $ventilator_value_result->estimated_peep = $entity->estimated_peep;
        $ventilator_value_result->status_use = Lang\VentilatorValue::convertToStatusUseName($entity->status_use);
        $ventilator_value_result->status_use_other = $entity->status_use_other;
        $ventilator_value_result->spo2 = $entity->spo2;
        $ventilator_value_result->etco2 = $entity->etco2;
        $ventilator_value_result->pao2 = $entity->pao2;
        $ventilator_value_result->paco2 = $entity->paco2;

        return $ventilator_value_result;
    }

    private static function convertToAdminVentilatorValueData(Collection $entities)
    {
        return array_map(
            function ($entity) {
                return self::convertToAdminVentilatorValueResult($entity);
            },
            $entities->all()
        );
    }

    public static function convertToAdminVentilatorValueDetail(VentilatorValue $entity)
    {
        $ventilator_value_result = new Response\Admin\VentilatorValueResult;

        $ventilator_value_result->id = $entity->id;
        $ventilator_value_result->patient_code = $entity->patient_code;
        $ventilator_value_result->registered_user_name = $entity->registered_user_name;
        $ventilator_value_result->organization_id = $entity->organization_id;
        $ventilator_value_result->fixed_flg = $entity->fixed_flg;
        $ventilator_value_result->confirmed_flg = $entity->confirmed_flg;
        $ventilator_value_result->height = $entity->height;
        $ventilator_value_result->weight = $entity->weight;
        $ventilator_value_result->gender = $entity->gender;
        $ventilator_value_result->airway_pressure = $entity->airway_pressure;
        $ventilator_value_result->air_flow = $entity->air_flow;
        $ventilator_value_result->o2_flow = $entity->o2_flow;
        $ventilator_value_result->fio2 = $entity->fio2;
        $ventilator_value_result->status_use = $entity->status_use;
        $ventilator_value_result->status_use_other = $entity->status_use_other;
        $ventilator_value_result->spo2 = $entity->spo2;
        $ventilator_value_result->etco2 = $entity->etco2;
        $ventilator_value_result->pao2 = $entity->pao2;
        $ventilator_value_result->paco2 = $entity->paco2;

        return $ventilator_value_result;
    }
}
