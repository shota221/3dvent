<?php

namespace App\Repositories;

use App\Models\VentilatorValueHistory;
use App\Models\VentilatorValue;
use App\Models\Ventilator;
use App\Models\Organization;
use App\Models\User;

class VentilatorValueHistoryRepository
{
    private static function query()
    {
        return VentilatorValueHistory::query();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();

        return self::createWhereClauseFromSearchValues(
            self::leftJoinVentilatorValueAndVentilatorAndOrganization($query), 
            $search_values)->count();
    }

    public static function findBySeachValuesAndLimitOffsetOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc(array $search_values, int $limit, int $offset)
    {
        $query = static::query();
        
        return self::createLimitOffsetClause(
            self::createWhereClauseFromSearchValuesOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc(static::leftJoinVentilatorValueAndVentilatorAndUserAndOrganization($query), $search_values),
            $limit,
            $offset
        )->get();
    }

    private static function createLimitOffsetClause($query, int $limit, int $offset)
    {
        $query->limit($limit)->offset($offset);
   
        return $query;
    }

    private static function createWhereClauseFromSearchValuesOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc($query, array $search_values)
    {
        $table =  VentilatorValueHistory::tableName();

        $organization_table = Organization::tableName();

        $ventilator_value_table = VentilatorValue::tableName();    
        
        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        $query->where($ventilator_value_table . '.confirmed_flg', $search_values['confirmed_flg']);

        if (isset($search_values['date_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['date_from']);
        }

        if (isset($search_values['date_to'])) {
            $query->where($table . '.created_at', '<=', $search_values['date_to']);
        }

        return $query->orderBy($ventilator_value_table . '.registered_at', 'ASC')->orderBy($table . '.created_at', 'ASC');
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        $table = VentilatorValueHistory::tableName();
       
        $ventilator_value_table = VentilatorValue::tableName();
        
        $organization_table = Organization::tableName();

        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        $query->where($ventilator_value_table . '.confirmed_flg', $search_values['confirmed_flg']);

        if (isset($search_values['date_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['date_from']);
        }

        if (isset($search_values['date_to'])) {
            $query->where($table . '.created_at', '<=', $search_values['date_to']);
        }

        
        return $query;
    }

    private static function leftJoinVentilatorValueAndVentilatorAndOrganization($query)
    {
        $table = VentilatorValueHistory::tableName();

        $ventilator_value_table = VentilatorValue::tableName();
        
        $ventilator_table = Ventilator::tableName();

        $organization_table = Organization::tableName();

        $query->leftjoin($ventilator_value_table, $table . '.ventilator_value_id', '=' , $ventilator_value_table . '.id');
        $query->leftjoin($ventilator_table, $ventilator_value_table . '.ventilator_id', '=' , $ventilator_table . '.id');
        $query->leftjoin($organization_table, $ventilator_table . '.organization_id', '=' , $organization_table . '.id');

        return $query;

    }

    private static function leftJoinVentilatorValueAndVentilatorAndUserAndOrganization($query)
    {
        $table = VentilatorValueHistory::tableName();

        $ventilator_value_table = VentilatorValue::tableName();
        
        $ventilator_table = Ventilator::tableName();

        $user_table = User::tableName();

        $organization_table = Organization::tableName();
        
        $query->leftjoin($ventilator_value_table, $table . '.ventilator_value_id', '=' , $ventilator_value_table . '.id');
        $query->leftjoin($ventilator_table, $ventilator_value_table . '.ventilator_id', '=' , $ventilator_table . '.id');
        $query->leftjoin($user_table, $ventilator_value_table . '.registered_user_id', '=' , $user_table . '.id');
        $query->leftjoin($organization_table, $ventilator_table . '.organization_id', '=' , $organization_table . '.id');
        
        $query->addSelect([
            $table . '.*',
            $ventilator_value_table . '.*',
            $ventilator_table . '.*',
            $user_table . '.name AS user_name',
            $organization_table . '.id AS organization_id',
        ]);

        return $query;
    }


    
}
