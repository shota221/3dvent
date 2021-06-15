<?php

namespace App\Repositories;

use App\Models\PatientValueHistory;
use App\Models\PatientValue;
use App\Models\Patient;
use App\Models\Organization;
use App\Models\User;

class PatientValueHistoryRepository
{
    private static function query()
    {
        return PatientValueHistory::query();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();
        $query = self::joinUserAndOrgazation($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();    
    }

    public static function findBySeachValuesAndLimitOffsetOrderByPatientValueRegisteredAtAscAndCreatedAtAsc(array $search_values, int $limit, int $offset)
    {
        $query = static::query();
        $query = self::joinPatientValueAndPatientAndOrganization($query);
        $query = self::createWhereClauseFromSearchValuesOrderByPatientValueRegisteredAtAscAndCreatedAtAsc($query, $search_values);
        $query = self::createLimitOffsetClause($query, $limit, $offset);

        return $query->get();
    }

    private static function createLimitOffsetClause($query, int $limit, int $offset)
    {
        $query->limit($limit)->offset($offset);
        return $query;
    }

    private static function createWhereClauseFromSearchValuesOrderByPatientValueRegisteredAtAscAndCreatedAtAsc($query, array $search_values)
    {
        $table = PatientValueHistory::tableName();

        $organization_table = Organization::tableName();

        $patient_value_table = PatientValue::tableName();    
        
        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        $query->where($organization_table . '.patient_obs_approved_flg', $search_values['patient_obs_approved_flg']);

        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['datetime_from']);
        }

        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '<', $search_values['datetime_from']);
        }

        return $query->orderBy($patient_value_table . '.registered_at', 'ASC')->orderBy($table . '.created_at', 'ASC');
    }


    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        $table = PatientValueHistory::tableName();
        
        $organization_table = Organization::tableName();
        
        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        $query->where($organization_table . '.patient_obs_approved_flg', $search_values['patient_obs_approved_flg']);
        
        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['datetime_from']);
        }

        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '<', $search_values['datetime_from']);
        }


        return $query;
    }


    private static function joinUserAndOrgazation($query)
    {
        $table = PatientValueHistory::tableName();
        
        $user_table = User::tableName();

        $organization_table = Organization::tableName();

        $query->join($user_table, $table . '.operated_user_id', '=' , $user_table . '.id');
        $query->join($organization_table, $user_table . '.organization_id', '=' , $organization_table . '.id');

        return $query;

    }

    private static function joinPatientValueAndPatientAndOrganization($query)
    {
        $table = PatientValueHistory::tableName();

        $patient_value_table = PatientValue::tableName();
        
        $patient_table = Patient::tableName();
        
        $organization_table = Organization::tableName();
        
        $query->join($patient_value_table, $table . '.patient_value_id', '=' , $patient_value_table . '.id');
        $query->join($patient_table, $patient_value_table . '.patient_id', '=' , $patient_table . '.id');
        $query->join($organization_table, $patient_table . '.organization_id', '=' , $organization_table . '.id');

        $query->addSelect([
            $table . '.*',
            $patient_value_table . '.*',
            $patient_table . '.patient_code AS patient_code',
            $organization_table . '.id AS organization_id',
            $organization_table . '.name AS organization_name',
        ]);

        return $query;
    }


    
}