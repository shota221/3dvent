<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Services\Support\DateUtil;

class PatientRepository
{
    private static function query()
    {
        return Patient::query();
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByOrganizationIdAndPatientCode(int $organization_id, string $patient_code)
    {
        return static::query()
            ->where('organization_id', $organization_id)
            ->where('patient_code', $patient_code)
            ->first();
    }

    public static function findOneByOrganizationIdAndId(int $organization_id, int $id)
    {
        return static::query()
            ->where('organization_id', $organization_id)
            ->where('id', $id)
            ->first();
    }

    public static function existsByOrganizationIdAndId(int $organization_id, int $id)
    {
        return static::query()
            ->where('organization_id', $organization_id)
            ->where('id', $id)
            ->exists();
    }

    public static function existsByPatientCodeAndOrganizationId($patient_code, $organization_id)
    {
        return static::query()
            ->where('organization_id', $organization_id)
            ->where('patient_code', $patient_code)
            ->exists();
    }

    public static function existsByPatientCodeAndOrganizationIdExceptId($patient_code, $organization_id, $id)
    {
        return static::query()
            ->where('organization_id', $organization_id)
            ->where('patient_code', $patient_code)
            ->where('id', '<>', $id)
            ->exists();
    }

    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id', $id)->value('organization_id');
    }

    public static function getPatientCodesByOrganizationIdAndPatientCodes($organziation_id, $patient_codes)
    {
        return static::query()->where('organization_id', $organziation_id)->whereIn('patient_code', $patient_codes)->pluck('patient_code');
    }

    public static function logicalDeleteByIds(array $ids)
    {
        return  static::query()->whereIn('id', $ids)->update(['deleted_at' => DateUtil::now()]);
    }
}
