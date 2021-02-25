<?php

namespace App\Repositories;

use App\Models\Patient;

class PatientRepository
{
    private static function query()
    {
        return Patient::query();
    }

    public static function findOneById(int $patient_id)
    {
        return static::query()->where('id',$patient_id)->first();
    }
}