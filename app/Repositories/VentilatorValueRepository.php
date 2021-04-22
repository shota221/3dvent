<?php

namespace App\Repositories;

use App\Models\Ventilator;

use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;

class VentilatorValueRepository
{
    private static function query()
    {
        return VentilatorValue::query();
    }

    public static function existsByVentilatorId($ventilator_id)
    {
        return static::query()->where('ventilator_id', $ventilator_id)->exists();
    }

    public static function findOneByVentilatorId($ventilator_id)
    {
        $table = VentilatorValue::tableName();
        return static::joinVentilator()->where('ventilator_id', $ventilator_id)->orderBy($table . '.created_at', 'DESC')->first();
    }

    private static function joinVentilator($query = null)
    {
        $table = VentilatorValue::tableName();

        $ventilator_table = Ventilator::tableName();

        return (!is_null($query) ? $query : static::query())
            ->join(
                $ventilator_table,
                function ($join) use ($table, $ventilator_table) {
                    $join
                        ->on($ventilator_table . '.id', '=', $table . '.ventilator_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $ventilator_table . '.patient_id AS patient_id',
            ]);
    }

    public static function updateFixedFlg($created_at_least)
    {
        $sql =
            'UPDATE ventilator_values 
          SET fixed_flg = ' . VentilatorValue::BOOLEAN_TRUE . ',fixed_at = "' . DateUtil::toDatetimeStr(DateUtil::now()) . '" 
          WHERE id IN (SELECT max_id FROM (SELECT MAX(id) as max_id FROM ventilator_values GROUP BY ventilator_id) AS TEMP) AND fixed_flg = ' . VentilatorValue::BOOLEAN_FALSE . ' AND created_at <= "' . $created_at_least . '"';

        \DB::update($sql);
    }

    public static function findBySeachValuesAndOffsetAndLimit(array $search_values, $limit = null, $offset = null)
    {
        return self::createLimitAndOffsetClause(
            self::createWhereClauseFromSearchValues(static::query(), $search_values),
            $limit,
            $offset
        )->get();
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        if (isset($search_values['ventilator_id'])) $query->where('ventilator_id', $search_values['ventilator_id']);

        if (isset($search_values['fixed_flg'])) $query->where('fixed_flg', $search_values['fixed_flg']);

        if (isset($search_values['user_id'])) $query->where('user_id', $search_values['user_id']);

        if (isset($search_values['confirmed_flg'])) $query->where('confirmed_flg', $search_values['confirmed_flg']);

        if (isset($search_values['confirmed_user_id'])) $query->where('confirmed_user_id', $search_values['confirmed_user_id']);

        return $query;
    }

    private static function createLimitAndOffsetClause($query, $limit = null, $offset = null)
    {
        if (!is_null($limit)) $query->limit($limit);

        if (!is_null($offset)) $query->offset($offset);

        return $query;
    }
}
