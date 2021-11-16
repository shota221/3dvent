<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Patient;
use App\Models\PatientValue;
use App\Models\User;
use App\Services\Support;

class PatientValueRepository
{
    private static function query()
    {
        return PatientValue::query();
    }

    private static function queryWithPatientByOrganizationId(int $organization_id)
    {
        return self::joinPatient(static::query())->where('patients.organization_id', $organization_id);
    }

    private static function queryByPatientObsUserId(int $patient_obs_user_id)
    {
        return static::query()->where('patient_obs_user_id', $patient_obs_user_id);
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByPatientId($patient_id)
    {
        return static::query()->where('patient_id', $patient_id)->orderBy('registered_at', 'DESC')->first();
    }

    public static function findOneByOrganizationIdAndPatientId(int $organization_id, int $patient_id)
    {
        return static::queryWithPatientByOrganizationId($organization_id)->where('patient_id', $patient_id)->orderBy('patient_values.registered_at', 'DESC')->select('patient_values.*')->first();
    }

    public static function findOneByPatientObsUserIdAndPatientId(int $patient_obs_user_id, int $patient_id)
    {
        return static::queryByPatientObsUserId($patient_obs_user_id)->where('patient_id', $patient_id)->orderBy('registered_at', 'DESC')->first();
    }

    public static function insertBulk(
        array $list_patient_value_for_bulk_insert,
        $user_id
    ) {
        $table = PatientValue::tableName();

        $count = count($list_patient_value_for_bulk_insert['patient_id']);

        $placeholder = substr(str_repeat(',(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $count), 1);

        $records = [];

        for ($i = 0; $i < $count; $i++) {
            $record = [
                $list_patient_value_for_bulk_insert['patient_id'][$i],
                $list_patient_value_for_bulk_insert['registered_at'][$i],
                $list_patient_value_for_bulk_insert['opt_out_flg'][$i],
                $list_patient_value_for_bulk_insert['age'][$i],
                $list_patient_value_for_bulk_insert['vent_disease_name'][$i],
                $list_patient_value_for_bulk_insert['other_disease_name_1'][$i],
                $list_patient_value_for_bulk_insert['other_disease_name_2'][$i],
                $list_patient_value_for_bulk_insert['used_place'][$i],
                $list_patient_value_for_bulk_insert['hospital'][$i],
                $list_patient_value_for_bulk_insert['national'][$i],
                $list_patient_value_for_bulk_insert['discontinuation_at'][$i],
                $list_patient_value_for_bulk_insert['outcome'][$i],
                $list_patient_value_for_bulk_insert['treatment'][$i],
                $list_patient_value_for_bulk_insert['adverse_event_flg'][$i],
                $list_patient_value_for_bulk_insert['adverse_event_contents'][$i],
                $user_id
            ];

            $records = array_merge($records, $record);
        }

        $query = <<<EOM
            INSERT INTO
                {$table}
                (patient_id,registered_at,opt_out_flg,age,vent_disease_name,other_disease_name_1,other_disease_name_2,used_place,hospital,national,discontinuation_at,outcome,treatment,adverse_event_flg,adverse_event_contents,patient_obs_user_id)
            VALUES
                {$placeholder}
        EOM;

        \DB::insert($query, $records);
    }

    public static function getIdsWithPatientAndOrganizationByOrganizationIdAndIds(int $organization_id, array $ids) {
        $query = self::joinPatientAndOrganization(static::query());
        return $query
            ->where('organizations.id', $organization_id)
            ->whereIn('patient_values.id', $ids)
            ->pluck('patient_values.id');
    }

    public static function getIdsWithPatientAndOrganizationByOrganizationIdAndUserIdAndIds(
        int $organization_id, 
        int $user_id,
        array $ids) 
    {
        $query = self::joinPatientAndOrganization(static::query());
        return $query
            ->where('organizations.id', $organization_id)
            ->where('patient_values.patient_obs_user_id', $user_id)
            ->whereIn('patient_values.id', $ids)
            ->pluck('patient_values.id');
    }

    public static function findOneWithPatientAndOrganizationById(int $id)
    {
        $query = self::joinPatientAndOrganization(static::query());
        $query->addSelect([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'organizations.id AS organization_id',
        ]);
        
        return $query->where('patient_values.id', $id)->first();
    }

    
    public static function findOneWithPatientAndOrganizationByOrganizationIdAndId(
        int $organization_id,
        int $id)
    {
        $query = self::joinPatientAndOrganization(static::query());
        $query->addSelect([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'organizations.id AS organization_id',
        ]);
        
        return $query
            ->where('organizations.id', $organization_id)
            ->where('patient_values.id', $id)
            ->first();
    }

    public static function searchWithPatientAndUserAndOrganization(
        array $search_values,
        int $limit,
        int $offset)
    {
        $query = self::queryWithPatientAndUserAndOrganizationBySearchValues(static::query(), $search_values);
        $query->addSelect([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'users.name AS registered_user_name',
        ]);

        return $query
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function searchWithPatientAndUserAndOrganizationByOrganizationId(
        array $search_values,
        int $organization_id,
        int $limit,
        int $offset)
    {
        $query = self::createWhereClauseFromOrganizationId(static::query(), $organization_id);
        $query = self::queryWithPatientAndUserAndOrganizationBySearchValues($query, $search_values, $organization_id);
        $query->select([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'users.name AS registered_user_name',
        ]);

        return $query
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function searchWithPatientAndUserAndOrganizationByOrganizationIdAndUserId(
        array $search_values,
        int $organization_id,
        int $user_id,
        int $limit,
        int $offset)
    {
        $query = self::createWhereClauseFromOrganizationId(static::query(), $organization_id);
        $query = self::createWhereClauseFromUserId($query, $user_id);
        $query = self::queryWithPatientAndUserAndOrganizationBySearchValues($query, $search_values, $organization_id);
        $query->select([
            'patient_values.*',
            'patients.patient_code',
            'organizations.name AS organization_name',
            'users.name AS registered_user_name',
        ]);

        return $query
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    private static function createWhereClauseFromOrganizationId($query, int $organization_id)
    {
        return $query->where('organizations.id', $organization_id);
    }

    private static function createWhereClauseFromUserId($query, int $user_id)
    {
        return $query->where('patient_values.patient_obs_user_id', $user_id);
    }

    public static function logicalDeleteByIds(array $ids)
    {
        return  static::query()->whereIn('id', $ids)->update(['deleted_at' => Support\DateUtil::now()]);
    }
    
    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();
        $query = self::joinPatientAndOrganization($query);
        $query = self::joinUser($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values);

        return $query->count();
    }

    public static function countByOrganizationIdAndSearchValues(int $organization_id, array $search_values)
    {
        $query = static::query();
        $query = self::joinPatientAndOrganization($query);
        $query = self::joinUser($query);
        $query = self::createWhereClauseFromSearchValues($query, $search_values, $organization_id);

        return $query->count();
    }

    public static function countByOrganizationIdAndUserIdAndSearchValues(
        int $organization_id, 
        int $user_id, 
        array $search_values)
    {
        $query = static::query();
        $query = self::joinPatientAndOrganization($query);
        $query = self::joinUser($query);
        $query = self::createWhereClauseFromUserId($query, $user_id);
        $query = self::createWhereClauseFromSearchValues($query, $search_values, $organization_id);

        return $query->count();
    }

    private static function queryWithPatientAndUserAndOrganizationBySearchValues(
        $query, 
        array $search_values,
        int $organization_id = null)
    {
        $query = self::joinPatientAndOrganization($query);
        $query = self::joinUser($query);
        return self::createWhereClauseFromSearchValues(
            $query,
            $search_values, 
            $organization_id);
    }

    private static function joinUser($query)
    {
        $query->join('users', 'patient_values.patient_obs_user_id', '=', 'users.id');

        return $query;
    }

    private static function joinPatient($query)
    {
        $query->join('patients', 'patient_values.patient_id', '=', 'patients.id');

        return $query;
    }

    private static function joinPatientAndOrganization($query)
    {
        $query->join('patients', 'patient_values.patient_id', '=', 'patients.id');
        $query->join('organizations', 'patients.organization_id', '=', 'organizations.id');

        return $query;
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values, int $organization_id = null)
    {
        if (isset($search_values['organization_id'])) {
            $query->where('organizations.id', $search_values['organization_id']);
            
            // 患者番号は組織名の絞込があった場合のみwhere句追加。
            if (isset($search_values['patient_code'])) {
                $patient_code = $search_values['patient_code'];
                $query->where('patients.patient_code', 'like', "%$patient_code%");
            }
        }

        // 組織ユーザーからの検索の場合$organization_idがセットされている。
        if (! is_null($organization_id)) {
            if (isset($search_values['patient_code'])) {
                $patient_code = $search_values['patient_code'];
                $query->where('patients.patient_code', 'like', "%$patient_code%");
            }
        }

        if (isset($search_values['registered_user_name'])) {
            $registered_user_name = $search_values['registered_user_name'];
            $query->where('users.name', 'like', "%$registered_user_name%");
        }

        if (isset($search_values['registered_at_from'])){
            $query->where('patient_values.registered_at', '>=', $search_values['registered_at_from']);
        }

        if (isset($search_values['registered_at_to'])){
            $query->where('patient_values.registered_at', '<=', $search_values['registered_at_to']);
        }

        return $query;
    }

    public static function getIdsByPatientIds(array $patient_ids)
    {
        return static::query()->whereIn('patient_id',$patient_ids)->pluck('id');
    }
}
