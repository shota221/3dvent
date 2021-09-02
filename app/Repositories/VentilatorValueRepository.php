<?php

namespace App\Repositories;

use App\Models\OrganizationSetting;
use App\Models\Ventilator;
use App\Models\User;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;
use GuzzleHttp\Psr7\FnStream;

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
                $ventilator_table . '.organization_id AS organization_id'
            ]);
    }

    private static function joinVentilatorsAndOrganizationSettings($query = null)
    {
        $table = VentilatorValue::tableName();

        $ventilator_table = Ventilator::tableName();

        $organization_setting_table = OrganizationSetting::tableName();

        return (!is_null($query) ? $query : static::query())
            ->join(
                $ventilator_table,
                function ($join) use ($table, $ventilator_table) {
                    $join
                        ->on($ventilator_table . '.id', '=', $table . '.ventilator_id');
                }
            )
            ->leftJoin(
                $organization_setting_table,
                function ($join) use ($ventilator_table, $organization_setting_table) {
                    $join
                        ->on($organization_setting_table . '.organization_id', '=', $ventilator_table . '.organization_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $organization_setting_table . '.ventilator_value_scan_interval AS ventilator_value_scan_interval'
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

    public static function queryByScannedAtIsNullOrderByRegisteredAtASC()
    {
        return static::joinVentilatorsAndOrganizationSettings()
            ->whereNull('ventilator_value_scanned_at')
            ->orderBy('registered_at', 'ASC');
    }

    public static function updateFixedFlgAndFixedAt($fix_ids, $fixed_at)
    {
        static::query()
            ->whereIn('id', $fix_ids)
            ->update(['fixed_flg' => VentilatorValue::FIX, 'fixed_at' => $fixed_at]);
    }

    public static function updateScannedAt($scanned_ids, $scanned_at)
    {
        static::query()
            ->whereIn('id', $scanned_ids)
            ->update(['ventilator_value_scanned_at' => $scanned_at]);
    }

    public static function insertBulk(
        array $ventilator_id_arr,
        array $appkey_id_arr,
        array $registered_at_arr,
        array $height_arr,
        array $weight_arr,
        array $gender_arr,
        array $ideal_weight_arr,
        array $airway_pressure_arr,
        array $total_flow_arr,
        array $air_flow_arr,
        array $o2_flow_arr,
        array $rr_arr,
        array $expiratory_time_arr,
        array $inspiratory_time_arr,
        array $vt_per_kg_arr,
        array $predicted_vt_arr,
        array $estimated_vt_arr,
        array $estimated_mv_arr,
        array $estimated_peep_arr,
        array $fio2_arr,
        array $status_use_arr,
        array $status_use_other_arr,
        array $spo2_arr,
        array $etco2_arr,
        array $pao2_arr,
        array $paco2_arr,
        array $fixed_flg_arr,
        array $fixed_at_arr,
        array $confirmed_flg_arr,
        array $confirmed_at_arr,
        $user_id
    ) {
        $table = VentilatorValue::tableName();

        $count = count($ventilator_id_arr);

        $placeholder = substr(str_repeat(',(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $count), 1);

        $records = [];

        for ($i = 0; $i < $count; $i++) {
            $record = [
                $ventilator_id_arr[$i],
                $appkey_id_arr[$i],
                $registered_at_arr[$i],
                $height_arr[$i],
                $weight_arr[$i],
                $gender_arr[$i],
                $ideal_weight_arr[$i],
                $airway_pressure_arr[$i],
                $total_flow_arr[$i],
                $air_flow_arr[$i],
                $o2_flow_arr[$i],
                $rr_arr[$i],
                $expiratory_time_arr[$i],
                $inspiratory_time_arr[$i],
                $vt_per_kg_arr[$i],
                $predicted_vt_arr[$i],
                $estimated_vt_arr[$i],
                $estimated_mv_arr[$i],
                $estimated_peep_arr[$i],
                $fio2_arr[$i],
                $status_use_arr[$i],
                $status_use_other_arr[$i],
                $spo2_arr[$i],
                $etco2_arr[$i],
                $pao2_arr[$i],
                $paco2_arr[$i],
                $fixed_flg_arr[$i],
                $fixed_at_arr[$i],
                $confirmed_flg_arr[$i],
                $confirmed_at_arr[$i],
                $user_id
            ];

            $records = array_merge($records, $record);
        }

        $query = <<<EOM
            INSERT INTO
                {$table}
                (ventilator_id,appkey_id,registered_at,height,weight,gender,ideal_weight,airway_pressure,total_flow,air_flow,o2_flow,rr,expiratory_time,inspiratory_time,vt_per_kg,predicted_vt,estimated_vt,estimated_mv,estimated_peep,fio2,status_use,status_use_other,spo2,etco2,pao2,paco2,fixed_flg,fixed_at,confirmed_flg,confirmed_at,registered_user_id)
            VALUES
                {$placeholder}
        EOM;

        \DB::insert($query, $records);
    }

    public static function listIdByVentilatorIds(array $ventilator_ids)
    {
        return static::query()->whereIn('ventilator_id', $ventilator_ids)->pluck('id');
    }
}
