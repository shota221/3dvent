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
        $table = PatientValue::tableName();
        $query = self::joinPatientAndOrganization(static::query());
        
        return $query->where($table . '.id', $id)->first();
    }

    public static function findWithPatientAndUserAndOrganizationBySearchValuesAndLimitAndOffsetOrderByCreatedAt(
        array $search_values,
        int $limit,
        int $offset)
    {
        return self::queryBySearchValuesOrderByCreatedAt($search_values)
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();
        $query = self::joinPatientAndUserAndOrganization($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();
    }

    private static function queryBySearchValuesOrderByCreatedAt(array $search_values)
    {
        $query = self::joinPatientAndUserAndOrganization(static::query());
        return self::createWhereClauseFromSearchValues(
            $query,
            $search_values)->orderBy('created_at', 'DESC');
    }

    private static function joinPatientAndUserAndOrganization($query)
    {
        $table = PatientValue::tableName();
        $patient_table = Patient::tableName();
        $user_table = User::tableName();
        $organization_table = Organization::tableName();

        $query->join($patient_table, $table . '.patient_id', '=', $patient_table . '.id');
        $query->join($user_table, $table . '.patient_obs_user_id', '=', $user_table . '.id');
        $query->join($organization_table, $patient_table . '.organization_id', '=', $organization_table . '.id');

        $query->addSelect([
            $table . '.*',
            $patient_table . '.patient_code',
            $organization_table . '.name AS organization_name',
            $user_table . '.name AS registered_user_name',
        ]);

        return $query;
    }

    private static function joinPatientAndOrganization($query)
    {
        $table = PatientValue::tableName();
        $patient_table = Patient::tableName();
        $organization_table = Organization::tableName();

        $query->join($patient_table, $table . '.patient_id', '=', $patient_table . '.id');
        $query->join($organization_table, $patient_table . '.organization_id', '=', $organization_table . '.id');

        $query->addSelect([
            $table . '.*',
            $patient_table . '.patient_code',
            $organization_table . '.name AS organization_name',
        ]);

        return $query;
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        $table = PatientValue::tableName();
        $patient_table = Patient::tableName();
        $user_table = User::tableName();
        $organization_table = Organization::tableName();

        if (isset($search_values['organization_name'])) {
            $query->where($organization_table . '.name', $search_values['organization_name']);
        }
        if (isset($search_values['patient_code'])) {
            $patient_code = $search_values['patient_code'];
            $query->where($patient_table . '.patient_code', 'like', "%$patient_code%");
        }
        if (isset($search_values['registered_user_name'])) {
            $query->where($user_table . '.name', $search_values['registered_user_name']);
        }
        if (isset($search_values['registered_at_from'])){
            $query->where($table . '.registered_at', '>=', $search_values['registered_at_from']);
        }
        if (isset($search_values['registered_at_to'])){
            $query->where($table . '.registered_at', '<=', $search_values['registered_at_to']);
        }

        return $query;
    }
}
