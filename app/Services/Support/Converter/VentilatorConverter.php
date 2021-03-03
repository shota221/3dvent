<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models\Ventilator;

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

        $res->estimated_peep = $estimated_peep ?? null;

        $res->fio2 = $fio2 ?? null;

        return $res;
    }

    public static function convertToVentilatorRegistrationResult($entity)
    {
        $res = new Response\Api\VentilatorResult;

        $res->ventilator_id = $entity->id;

        $res->organization_name = $entity->organization_name ?? null;

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->ventilator_id = 1;

        $res->estimated_vt = '446';

        $res->estimated_mv = '6.9';

        $res->estimated_peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->airway_pressure = '23.6';

        $res->air_flow = '12.0';

        $res->o2_flow = '6.0';

        $res->rr = '15.9';

        $res->estimated_vt = '446';

        $res->estimated_mv = '6.9';

        $res->estimated_peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueUpdateResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->fixed_flg = true;

        return $res;
    }

    public static function convertToVentilatorEntity(Form\VentilatorCreateForm $form)
    {
        $entity = new Ventilator;

        $entity->gs1_code = $form->gs1_code;

        if (!is_null($form->latitude) && !is_null($form->longitude)) {
            $entity->location = ['latitude' => $form->latitude, 'longitude' => $form->longitude];
        }

        $entity->organization_id = $form->organization_id ?? null;

        $entity->registered_user_id = $form->registered_user_id ?? null;

        return $entity;
    }

    public static function convertToVentilatorValueEntity()
    {
    }
}
