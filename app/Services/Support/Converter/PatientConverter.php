<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class PatientConverter
{
    public static function convertToPatientRegistrationResult()
    {
        $res = new Response\Api\PatientResult;

        $res->patient_id = 1;

        return $res;
    }

    public static function convertToPatientResult($other_attrs)
    {
        $res = new Response\Api\PatientResult;

        $res->nickname = 'テスト患者1';

        $res->height = '169.5';

        $res->weight = '60.3';

        $res->other_attrs = $other_attrs;

        return $res;
    }
}
