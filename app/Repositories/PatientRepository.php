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
        return static::query()->where('id', $patient_id)->first();
    }

    public static function existsByPatientCodeAndOrganizationId($patient_code, $organization_id)
    {
        return static::query()
            ->where('patient_code', $patient_code)
            ->where('organization_id', $organization_id)
            ->exists();
    }

    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id',$id)->value('organization_id');
    }
}
