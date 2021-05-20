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

    //TODO DELETE ME
    public static function updateFixedFlg($now, $interval)
    {
        $table = VentilatorValue::tableName();

        //指定時間以前のレコードのうちscanned_atがnullのものをはいて、そこから指定時間以内に同呼吸器に対してのレコードがなければfixed_flgとfixed_atを記録する。
        $sql_to_fix =
            'UPDATE ' . $table . ' AS a 
            SET a.fixed_flg = ' . VentilatorValue::BOOLEAN_TRUE . ',a.fixed_at = "' . $now . '" 
            WHERE a.ventilator_value_scanned_at IS NULL 
            AND a.registered_at<=DATE_SUB("' . $now . '",INTERVAL ' . $interval . ' HOUR) 
            AND NOT EXISTS(
                SELECT 1 FROM (SELECT id,ventilator_id, registered_at FROM ventilator_values WHERE ventilator_value_scanned_at IS NULL) AS b 
                WHERE b.id>a.id AND b.ventilator_id = a.ventilator_id 
                AND b.registered_at BETWEEN a.registered_at 
                AND DATE_ADD(a.registered_at,INTERVAL ' . $interval . ' HOUR))';

        //↑ではいたもの全てにscanned_atを記録する
        $sql_to_record_scanned_at =
            'UPDATE ' . $table . '  
            SET ventilator_value_scanned_at = "' . $now . '" 
            WHERE ventilator_value_scanned_at IS NULL 
            AND registered_at<=DATE_SUB("' . $now . '",INTERVAL ' . $interval . ' HOUR)';

        \DB::update($sql_to_fix);
        \DB::update($sql_to_record_scanned_at);
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
     * チャンク処理をおこなうためqueryで返す。
     * @param [type] $registered_at_to
     */
    public static function queryByScannedAtIsNullAndRegisteredAtTo($registered_at_to)
    {
        return static::query()->whereNull('ventilator_value_scanned_at')
            ->where('registered_at', '<=', $registered_at_to);
    }

    /**
     * fixed_flg判定用。スキャン対象きき観察研究データのregistered_atから指定インターバル以内に登録されたデータが存在するかどうか。
     *
     * @param [type] $ventilator_id
     * @param [type] $registered_at_from
     * @param [type] $registered_at_to
     */
    public static function existsByVentilatorIdAndRegisteredAtFromTo($ventilator_id, $registered_at_from, $registered_at_to)
    {
        return static::query()->where('ventilator_id', $ventilator_id)
            ->where('registered_at', '>', $registered_at_from)
            ->where('registered_at', '<=', $registered_at_to)
            ->exists();
    }
}
