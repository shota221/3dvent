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

    private static function leftJoinOrganizationSettings($query = null)
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
        return static::leftJoinOrganizationSettings()
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
}
