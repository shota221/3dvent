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

    public static function updateFixedFlg($created_at_least)
    {
        $sql = 
        'UPDATE ventilator_values 
          SET fixed_flg = '.VentilatorValue::BOOLEAN_TRUE.',fixed_at = "'.DateUtil::toDatetimeStr(DateUtil::now()).'" 
          WHERE id IN (SELECT max_id FROM (SELECT MAX(id) as max_id FROM ventilator_values GROUP BY ventilator_id) AS TEMP) AND fixed_flg = '.VentilatorValue::BOOLEAN_FALSE.' AND created_at <= "'.$created_at_least.'"';

        \DB::update($sql);
    }
}
