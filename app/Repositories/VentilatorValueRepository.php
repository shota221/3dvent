<?php

namespace App\Repositories;

use App\Models\Ventilator;
use App\Models\User;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;

class VentilatorValueRepository
{
    private static function query()
    {
        return VentilatorValue::query();
    }

    public static function findOneById($id)
    {
        return static::query()->where('id', $id)->first();
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

    public static function findBySeachValuesAndLimitOffsetOrderByRegisteredAtDesc(array $search_values, $limit = null, $offset = null)
    {
        return self::createLimitOffsetClause(
            self::createWhereClauseFromSearchValuesOrderByRegisteredAtDesc(static::leftJoinUser(), $search_values),
            $limit,
            $offset
        )->get();
    }

    private static function createWhereClauseFromSearchValuesOrderByRegisteredAtDesc($query, array $search_values)
    {
        if (isset($search_values['ventilator_id'])) $query->where('ventilator_id', $search_values['ventilator_id']);

        if (isset($search_values['fixed_flg'])) $query->where('fixed_flg', $search_values['fixed_flg']);

        if (isset($search_values['user_id'])) $query->where('user_id', $search_values['user_id']);

        if (isset($search_values['confirmed_flg'])) $query->where('confirmed_flg', $search_values['confirmed_flg']);

        if (isset($search_values['confirmed_user_id'])) $query->where('confirmed_user_id', $search_values['confirmed_user_id']);

        return $query->orderBy('registered_at', 'DESC');
    }

    private static function createLimitOffsetClause($query, $limit = null, $offset = 0)
    {
        if (!is_null($limit)) $query->limit($limit)->offset($offset);

        return $query;
    }

    private static function leftJoinUser($query = null)
    {
        $table = VentilatorValue::tableName();

        $user_table = User::tableName();

        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                $user_table,
                function ($join) use ($table, $user_table) {
                    $join
                        ->on($user_table . '.id', '=', $table . '.registered_user_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $user_table . '.name AS registered_user_name',
            ]);
    }


    /**
     * バッチスキャン対象:未スキャンかつregistered_atが現在時刻から指定インターバルより過去の危機観察研究データ(現在時刻から指定インターバル以内のデータはfixed_flgが立ち得ないため)
     * を持つventilator_idを配列として返す。
     * @param [type] $registered_at_to
     */
    public static function listOfVentilatorIdByScannedAtIsNullAndRegisteredAtTo($registered_at_to)
    {
        return static::query()
            ->select('ventilator_id')
            ->whereNull('ventilator_value_scanned_at')
            ->where('registered_at', '<=', $registered_at_to)
            ->groupBy('ventilator_id')
            ->pluck('ventilator_id');
    }

    public static function findByVenitilatorIdAndScannedAtIsNull($ventilator_id)
    {
        return static::query()
            ->where('ventilator_id', $ventilator_id)
            ->whereNull('ventilator_value_scanned_at')
            ->orderBy('registered_at','DESC')
            ->get();
    }

    public static function updateBulkFixedFlgAndFixedAt($ids_to_fix,$fixed_at)
    {
        static::query()
        ->whereIn('id',$ids_to_fix)
        ->update(['fixed_flg'=>1,'fixed_at'=>$fixed_at]);
    }

    public static function updateBulkScannedAt($ids_scanned, $scanned_at)
    {
        static::query()
        ->whereIn('id',$ids_scanned)
        ->update(['ventilator_value_scanned_at'=>$scanned_at]);
    }
}
