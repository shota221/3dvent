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
        $query = self::joinVentilatorValueAndVentilatorAndOrganization($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();
    }

    public static function findBySeachValuesAndLimitOffsetOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc(array $search_values, int $limit, int $offset)
    {
        $query = static::query();
        $query = self::joinVentilatorValueAndVentilatorAndUserAndOrganization($query);
        $query = self::createWhereClauseFromSearchValuesOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc($query, $search_values);
        $query = self::createLimitOffsetClause($query, $limit, $offset);

        return $query->get();
        
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

    private static function joinVentilatorValueAndVentilatorAndOrganization($query)
    {
        $table = VentilatorValueHistory::tableName();

        $ventilator_value_table = VentilatorValue::tableName();
        
        $ventilator_table = Ventilator::tableName();

        $organization_table = Organization::tableName();

        $query->join($ventilator_value_table, $table . '.ventilator_value_id', '=' , $ventilator_value_table . '.id');
        $query->join($ventilator_table, $ventilator_value_table . '.ventilator_id', '=' , $ventilator_table . '.id');
        $query->join($organization_table, $ventilator_table . '.organization_id', '=' , $organization_table . '.id');

        return $query;

    }

    private static function joinVentilatorValueAndVentilatorAndUserAndOrganization($query)
    {
        $table = VentilatorValueHistory::tableName();

        $ventilator_value_table = VentilatorValue::tableName();
        
        $ventilator_table = Ventilator::tableName();

        $user_table = User::tableName();

        $organization_table = Organization::tableName();
        
        $query->join($ventilator_value_table, $table . '.ventilator_value_id', '=' , $ventilator_value_table . '.id');
        $query->join($ventilator_table, $ventilator_value_table . '.ventilator_id', '=' , $ventilator_table . '.id');
        // ventilator_valueは未ログインユーザーが登録する場合もあるためleftjoin
        $query->leftjoin($user_table, $ventilator_value_table . '.registered_user_id', '=' , $user_table . '.id');
        $query->join($organization_table, $ventilator_table . '.organization_id', '=' , $organization_table . '.id');
        
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
