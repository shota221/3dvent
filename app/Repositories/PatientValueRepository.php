<?php

namespace App\Repositories;

use App\Models\PatientValue;

class PatientValueRepository
{
    private static function query()
    {
        return PatientValue::query();
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }
    
    public static function findOneByPatientId($patient_id)
    {
        return static::query()->where('patient_id', $patient_id)->orderBy('registered_at','DESC')->first();
    }
}
