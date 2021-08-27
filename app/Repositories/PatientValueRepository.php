<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Patient;
use App\Models\PatientValue;
use App\Models\User;

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

    public static function findOneWithPatientAndOrganizationById(int $id)
    {
        $query = self::joinPatientAndOrganization(static::query());
        $query->addSelect([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
        ]);
        
        return $query->where('patient_values.id', $id)->first();
    }

    public static function findWithPatientAndUserAndOrganizationBySearchValuesAndLimitAndOffsetOrderByCreatedAt(
        array $search_values,
        int $limit,
        int $offset)
    {
        $query = self::queryBySearchValues($search_values);
        $query->addSelect([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'users.name AS registered_user_name',
        ]);

        return $query
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();
        $query = self::joinPatientAndOrganization($query);
        $query = self::joinUser($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();
    }

    private static function queryBySearchValues(array $search_values)
    {
        $query = self::joinPatientAndOrganization(static::query());
        $query = self::joinUser($query);
        return self::createWhereClauseFromSearchValues(
            $query,
            $search_values);
    }

    private static function joinUser($query)
    {
        $query->join('users', 'patient_values.patient_obs_user_id', '=', 'users.id');

        return $query;
    }

    private static function joinPatientAndOrganization($query)
    {
        $query->join('patients', 'patient_values.patient_id', '=', 'patients.id');
        $query->join('organizations', 'patients' . '.organization_id', '=', 'organizations.id');

        return $query;
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        if (isset($search_values['organization_name'])) {
            $query->where('organizations.name', $search_values['organization_name']);
            
            // 患者番号、登録者は組織名の絞込があった場合のみwhere句追加。
            if (isset($search_values['patient_code'])) {
                $patient_code = $search_values['patient_code'];
                $query->where('patients.patient_code', 'like', "%$patient_code%");
            }
            if (isset($search_values['registered_user_name'])) {
                $query->where('users.name', $search_values['registered_user_name']);
            }
        }

        if (isset($search_values['registered_at_from'])){
            $query->where('patient_values.registered_at', '>=', $search_values['registered_at_from']);
        }

        if (isset($search_values['registered_at_to'])){
            $query->where('patient_values.registered_at', '<=', $search_values['registered_at_to']);
        }

        return $query;
    }
}
