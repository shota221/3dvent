<?php

namespace App\Repositories;

use App\Models\Ventilator;

use App\Models\VentilatorValue;

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
        return static::joinVentilator()->where('ventilator_id', $ventilator_id)->orderBy($table.'.created_at', 'DESC')->first();
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

    /**
     * ventilatorvalueからventilatorIdごとに最新データを取り、指定時間より過去のデータであれば取得する
     */
    public static function queryToUpdateFixedFlg($date_time)
    {
        $table = VentilatorValue::tableName();
        return static::query()->wherein('id',self::groupByVentilatorId())->where('created_at','<=',$date_time)->where('fixed_flg',0);
    }

    private static function groupByVentilatorId($query = null)
    {
        return (!is_null($query) ? $query : static::query())->select(\DB::raw('MAX(id) As id'))->groupBy('ventilator_id');
    }
}
