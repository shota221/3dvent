<?php

namespace App\Repositories;

use App\Models\VentilatorBug;
use App\Models\Ventilator;
use App\Models\Organization;
use App\Models\User;

class VentilatorBugRepository
{
    private static function query()
    {
        return VentilatorBug::query();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();
        $query = self::joinVentilatorAndOrganization($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();
    }

    public static function findBySeachValuesAndLimitOffsetOrderByRegisteredAtAsc(array $search_values, int $limit, int $offset)
    {
        $query = static::query();
        $query = self::joinVentilatorAndOrganization($query);
        $query = self::createWhereClauseFromSearchValuesOrderByRegisteredAtAsc($query, $search_values);
        $query = self::createLimitOffsetClause($query, $limit, $offset);

        return $query->get();
        
    }

    public static function findByVentilatorId($ventilator_id)
    {
        return static::leftJoinUser()->where('ventilator_id',$ventilator_id)->get();
    }
   
    private static function createLimitOffsetClause($query, int $limit, int $offset)
    {
        $query->limit($limit)->offset($offset);
   
        return $query;
    }

    private static function createWhereClauseFromSearchValuesOrderByRegisteredAtAsc($query, array $search_values)
    {
        $table =  VentilatorBug::tableName();

        $organization_table = Organization::tableName();

        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['datetime_from']);
        }

        if (isset($search_values['datetime_to'])) {
            $query->where($table . '.created_at', '<', $search_values['datetime_to']);
        }

        return $query->orderBy($table . '.created_at', 'ASC');
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        $table = VentilatorBug::tableName();
        $organization_table = Organization::tableName();

        $query->where($organization_table . '.edcid', $search_values['edcid']);
        
        if (isset($search_values['datetime_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['datetime_from']);
        }

        if (isset($search_values['datetime_to'])) {
            $query->where($table . '.created_at', '<', $search_values['datetime_to']);
        }

        return $query;
    }

    private static function joinVentilatorAndOrganization($query)
    {
        $table = VentilatorBug::tableName();

        $ventilator_table = Ventilator::tableName();

        $organization_table = Organization::tableName();
        
        $query->join($ventilator_table, $table . '.ventilator_id', '=' , $ventilator_table . '.id');
        $query->join($organization_table, $ventilator_table . '.organization_id', '=' , $organization_table . '.id');
        
        return $query;

    }

    private static function leftJoinUser($query = null)
    {
        $table = VentilatorBug::tableName();

        $user_table = User::tableName();

        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                $user_table,
                function ($join) use ($table, $user_table) {
                    $join
                        ->on($user_table . '.id', '=', $table . '.bug_registered_user_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $user_table . '.name AS registered_user_name',
            ]);
    }
}
