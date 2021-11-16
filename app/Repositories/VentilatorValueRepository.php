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

    private static function queryWithVentilatorsByOrganizationId(int $organization_id)
    {
        return self::joinVentilators()->where('ventilators.organization_id', $organization_id);
    }

    private static function queryByRegisteredUserId(int $registered_user_id)
    {
        return static::query()->where('registered_user_id', $registered_user_id);
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByOrganizationIdAndId(int $organization_id, int $id)
    {
        return self::queryWithVentilatorsByOrganizationId($organization_id)->where('ventilator_values.id', $id)
            ->select('ventilator_values.*')->first();
    }

    public static function findOneByRegisteredUserIdAndId(int $registered_user_id, int $id)
    {
        return static::queryByRegisteredUserId($registered_user_id)->where('id', $id)->first();
    }

    public static function findOneWithPatientAndOrganizationAndRegisteredUserById($id)
    {
        $query = self::joinVentilatorsAndPatientsAndOrganizations();
        $query = self::leftJoinUsers($query);
        return $query->where('ventilator_values.id', $id)->select(
            'ventilator_values.*',
            'patients.patient_code AS patient_code',
            'users.name AS registered_user_name',
            'organizations.id AS organization_id'
        )->first();
    }

    public static function findOneWithPatientAndOrganizationAndRegisteredUserByOrganizationIdAndId($organization_id, $id)
    {
        $query = self::queryWithVentilatorsByOrganizationId($organization_id)
            ->leftJoin(
                'patients',
                'ventilators.patient_id',
                '=',
                'patients.id'
            )->leftJoin(
                'organizations',
                'ventilators.organization_id',
                '=',
                'organizations.id'
            );

        $query = self::leftJoinUsers($query);
        return $query->where('ventilator_values.id', $id)->select(
            'ventilator_values.*',
            'patients.patient_code AS patient_code',
            'users.name AS registered_user_name',
            'organizations.id AS organization_id'
        )->first();
    }

    public static function existsByVentilatorId(int $ventilator_id)
    {
        return static::query()->where('ventilator_id', $ventilator_id)->exists();
    }

    public static function existsByVentilatorIds(array $ventilator_ids)
    {
        return static::query()->whereIn('ventilator_id', $ventilator_ids)->exists();
    }


    public static function findOneByVentilatorId(int $ventilator_id)
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
        $query =  self::createWhereClauseFromSearchValuesOrderByRegisteredAtDesc(static::leftJoinUsers(), $search_values)
            ->select('ventilator_values.*', 'users.name AS registered_user_name');

        return self::createLimitOffsetClause(
            $query,
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

    private static function leftJoinUsers($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                'users',
                'ventilator_values.registered_user_id',
                '=',
                'users.id'
            );
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
        array $list_ventilator_value_for_bulk_insert,
        $user_id
    ) {
        $table = VentilatorValue::tableName();

        $count = count($list_ventilator_value_for_bulk_insert['ventilator_id']);

        $placeholder = substr(str_repeat(',(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $count), 1);

        $records = [];

        for ($i = 0; $i < $count; $i++) {
            $record = [
                $list_ventilator_value_for_bulk_insert['ventilator_id'][$i],
                $list_ventilator_value_for_bulk_insert['appkey_id'][$i],
                $list_ventilator_value_for_bulk_insert['registered_at'][$i],
                $list_ventilator_value_for_bulk_insert['height'][$i],
                $list_ventilator_value_for_bulk_insert['weight'][$i],
                $list_ventilator_value_for_bulk_insert['gender'][$i],
                $list_ventilator_value_for_bulk_insert['ideal_weight'][$i],
                $list_ventilator_value_for_bulk_insert['airway_pressure'][$i],
                $list_ventilator_value_for_bulk_insert['total_flow'][$i],
                $list_ventilator_value_for_bulk_insert['air_flow'][$i],
                $list_ventilator_value_for_bulk_insert['o2_flow'][$i],
                $list_ventilator_value_for_bulk_insert['rr'][$i],
                $list_ventilator_value_for_bulk_insert['expiratory_time'][$i],
                $list_ventilator_value_for_bulk_insert['inspiratory_time'][$i],
                $list_ventilator_value_for_bulk_insert['vt_per_kg'][$i],
                $list_ventilator_value_for_bulk_insert['predicted_vt'][$i],
                $list_ventilator_value_for_bulk_insert['estimated_vt'][$i],
                $list_ventilator_value_for_bulk_insert['estimated_mv'][$i],
                $list_ventilator_value_for_bulk_insert['estimated_peep'][$i],
                $list_ventilator_value_for_bulk_insert['fio2'][$i],
                $list_ventilator_value_for_bulk_insert['status_use'][$i],
                $list_ventilator_value_for_bulk_insert['status_use_other'][$i],
                $list_ventilator_value_for_bulk_insert['spo2'][$i],
                $list_ventilator_value_for_bulk_insert['etco2'][$i],
                $list_ventilator_value_for_bulk_insert['pao2'][$i],
                $list_ventilator_value_for_bulk_insert['paco2'][$i],
                $list_ventilator_value_for_bulk_insert['fixed_flg'][$i],
                $list_ventilator_value_for_bulk_insert['fixed_at'][$i],
                $list_ventilator_value_for_bulk_insert['confirmed_flg'][$i],
                $list_ventilator_value_for_bulk_insert['confirmed_at'][$i],
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

    public static function getIdsByVentilatorIds(array $ventilator_ids)
    {
        return static::query()->whereIn('ventilator_id', $ventilator_ids)->pluck('id');
    }

    public static function searchWithUsersAndVentilatorsAndPatientsAndOrganizations(array $search_values, $limit, $offset)
    {
        $query = self::queryBySearchValues($search_values)
            ->select(
                'ventilator_values.*',
                'ventilators.gs1_code AS gs1_code',
                'patients.patient_code AS patient_code',
                'organizations.name AS organization_name',
                'users.name AS registered_user_name'
            );

        return self::createLimitOffsetClause($query, $limit, $offset)
            ->orderBy('ventilator_values.created_at', 'DESC')
            ->get();
    }

    public static function searchWithUsersAndVentilatorsAndPatientsAndOrganizationsByOrganizationId($organization_id, array $search_values, $limit, $offset)
    {
        $query = self::queryByOrganizationIdAndSearchValues($organization_id, $search_values)
            ->select(
                'ventilator_values.*',
                'ventilators.gs1_code AS gs1_code',
                'patients.patient_code AS patient_code',
                'users.name AS registered_user_name'
            );

        return self::createLimitOffsetClause($query, $limit, $offset)
            ->orderBy('ventilator_values.created_at', 'DESC')
            ->get();
    }

    public static function searchWithUsersAndVentilatorsAndPatientsAndOrganizationsByOrganizationIdAndRegisteredUserId(
        int $organization_id,
        int $registered_user_id,  
        array $search_values, 
        int $limit, 
        int $offset)
    {
        $query = self::queryByOrganizationIdAndSearchValues($organization_id, $search_values)
        ->select(
            'ventilator_values.*',
            'ventilators.gs1_code AS gs1_code',
            'patients.patient_code AS patient_code',
            'users.name AS registered_user_name'
        );

        $query = self::createWhereClauseFromRegisteredUserId($query, $registered_user_id);

        return self::createLimitOffsetClause($query, $limit, $offset)
            ->orderBy('ventilator_values.created_at', 'DESC')
            ->get();
    }

    private static function createWhereClauseFromRegisteredUserId($query, int $registered_user_id)
    {
        return $query->where('ventilator_values.registered_user_id', $registered_user_id);
    }

    public static function countBySearchValues(array $search_values)
    {
        return self::queryBySearchValues($search_values)->count();
    }

    public static function countByOrganizationIdAndSearchValues($organization_id, array $search_values)
    {
        return self::queryByOrganizationIdAndSearchValues($organization_id, $search_values)->count();
    }

    public static function countByOrganizationIdAndUserIdAndSearchValues(
        int $organization_id,
        int $registered_user_id,
        array $search_values)
    {
        $query = self::queryByOrganizationIdAndSearchValues($organization_id, $search_values);
        $query = self::createWhereClauseFromRegisteredUserId($query, $registered_user_id);
        
        return $query->count();
    }

    private static function queryBySearchValues(array $search_values)
    {
        $query = self::joinVentilatorsAndPatientsAndOrganizations();
        $query = self::leftJoinUsers($query);
        return self::createWhereClauseFromSearchValues($query, $search_values);
    }

    private static function queryByOrganizationIdAndSearchValues($organization_id, array $search_values)
    {
        $query = self::queryWithVentilatorsByOrganizationId($organization_id)
            ->leftJoin(
                'patients',
                'ventilators.patient_id',
                '=',
                'patients.id'
            )->leftJoin(
                'organizations',
                'ventilators.organization_id',
                '=',
                'organizations.id'
            );
        $query = self::leftJoinUsers($query);
        return self::createWhereClauseFromSearchValuesInOrganziationScope($query, $search_values);
    }

    private static function joinVentilatorsAndPatientsAndOrganizations($query = null)
    {
        return self::joinVentilators($query)
            ->leftJoin(
                'patients',
                'ventilators.patient_id',
                '=',
                'patients.id'
            )->leftJoin(
                'organizations',
                'ventilators.organization_id',
                '=',
                'organizations.id'
            );
    }

    private static function joinVentilators($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->join(
                'ventilators',
                'ventilator_values.ventilator_id',
                '=',
                'ventilators.id'
            );
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {
        if (isset($search_values['ventilator_id'])) $query->where('ventilator_values.ventilator_id', $search_values['ventilator_id']);

        if (isset($search_values['organization_id'])) {
            $query->where('organizations.id', $search_values['organization_id']);
            if (isset($search_values['gs1_code'])) $query->where('ventilators.gs1_code', $search_values['gs1_code']);
            if (isset($search_values['patient_code'])) {
                $patient_code = $search_values['patient_code'];
                $query->where('patients.patient_code', 'like', "%$patient_code%");
            }
        }

        if (isset($search_values['registered_user_name'])) {
            $registered_user_name = $search_values['registered_user_name'];
            $query->where('users.name', 'like', "%$registered_user_name%");
        }

        if (isset($search_values['registered_at_from'])) {
            $query->where('ventilator_values.registered_at', '>=', $search_values['registered_at_from']);
        }

        if (isset($search_values['registered_at_to'])) {
            $query->where('ventilator_values.registered_at', '<=', $search_values['registered_at_to']);
        }

        if (isset($search_values['fixed_flg'])) $query->where('fixed_flg', $search_values['fixed_flg']);

        if (isset($search_values['confirmed_flg'])) $query->where('confirmed_flg', $search_values['confirmed_flg']);

        return $query;
    }

    /**
     * org内での絞り込みの場合
     *
     * @param [type] $query
     * @param [type] $search_values
     */
    private static function createWhereClauseFromSearchValuesInOrganziationScope($query, array $search_values)
    {
        if (isset($search_values['ventilator_id'])) $query->where('ventilator_values.ventilator_id', $search_values['ventilator_id']);

        if (isset($search_values['gs1_code'])) $query->where('ventilators.gs1_code', $search_values['gs1_code']);

        if (isset($search_values['patient_code'])) {
            $patient_code = $search_values['patient_code'];
            $query->where('patients.patient_code', 'like', "%$patient_code%");
        }

        if (isset($search_values['registered_user_name'])) {
            $registered_user_name = $search_values['registered_user_name'];
            $query->where('users.name', 'like', "%$registered_user_name%");
        }

        if (isset($search_values['registered_at_from'])) {
            $query->where('ventilator_values.registered_at', '>=', $search_values['registered_at_from']);
        }

        if (isset($search_values['registered_at_to'])) {
            $query->where('ventilator_values.registered_at', '<=', $search_values['registered_at_to']);
        }

        if (isset($search_values['fixed_flg'])) $query->where('fixed_flg', $search_values['fixed_flg']);

        if (isset($search_values['confirmed_flg'])) $query->where('confirmed_flg', $search_values['confirmed_flg']);

        return $query;
    }

    public static function logicalDeleteByIds(array $ids)
    {
        return  static::query()->whereIn('id', $ids)->update(['deleted_at' => DateUtil::now()]);
    }

    public static function logicalDeleteByOrganizationIdAndIds($organization_id, array $ids)
    {
        return  self::queryWithVentilatorsByOrganizationId($organization_id)->whereIn('ventilator_values.id', $ids)->update(['ventilator_values.deleted_at' => DateUtil::now()]);
    }

    public static function getIdsByOrganizationIdAndIds(int $organization_id, array $ids)
    {
        return self::queryWithVentilatorsByOrganizationId($organization_id)->whereIn('ventilator_values.id', $ids)->pluck('ventilator_values.id');
    }

    public static function getIdsByOrganizationIdAndRegisteredUserIdAndIds(
        int $organization_id,
        int $registered_user_id, 
        array $ids)
    {
        return self::queryWithVentilatorsByOrganizationId($organization_id)
            ->where('ventilator_values.registered_user_id', $registered_user_id)
            ->whereIn('ventilator_values.id', $ids)->pluck('ventilator_values.id');
    }

    public static function getIdsByIds(array $ids)
    {
        return static::query()->whereIn('id', $ids)->pluck('id');
    }
}
