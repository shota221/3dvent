<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Patient;
use App\Models\PatientValue;
use App\Models\User;
use App\Models\Ventilator;
use App\Models\VentilatorBug;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;

class VentilatorRepository
{
    private static function query()
    {
        return Ventilator::query();
    }

    private static function queryByOrganizationId($organization_id)
    {
        return static::query()->where('ventilators.organization_id',$organization_id);
    }

    private static function querySelectGeom()
    {
        return static::query()->select([
            '*',
            \DB::raw('ST_X(location)       as lng'),
            \DB::raw('ST_Y(location)       as lat'),
            \DB::raw('ST_ASTEXT(location)  as geomtxt')
        ]);
    }

    public static function existsByGs1Code($gs1_code)
    {
        return static::query()->where('gs1_code', $gs1_code)->exists();
    }

    public static function existsById($id)
    {
        return static::query()->where('id', $id)->exists();
    }

    public static function findOneById($id)
    {
        $table = Ventilator::tableName();
        return static::query()->where('id', $id)->first();
    }

    public static function logicalDeleteByIds($ids)
    {
        return static::query()->whereIn('id', $ids)->update(['deleted_at' => DateUtil::now(), 'active' => null]);
    }

    public static function findOneByGs1Code($gs1_code)
    {
        return static::leftJoinOrganization()
            ->addSelect([
                'ventilators.*',
                'organizations.name AS organization_name',
                'organizations.code AS organization_code'
            ])
            ->where('gs1_code', $gs1_code)
            ->orderBy('ventilators.created_at', 'DESC')
            ->first();
    }

    private static function leftJoinOrganization($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin('organizations', 'organizations.id', '=', 'ventilators.organization_id');
    }

    private static function leftJoinUser($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'ventilators.registered_user_id'
            );
    }

    private static function leftJoinVentilatorBug($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                'ventilator_bugs',
                'ventilator_bugs.ventilator_id',
                '=',
                'ventilators.id'
            );
    }

    private static function leftJoinPatient($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                'patients',
                'patients.id',
                '=',
                'ventilators.patient_id'
            );
    }

    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id', $id)->value('organization_id');
    }

    public static function getPatientCodeById(int $id)
    {
        return static::leftJoinPatient()
            ->addSelect([
                'ventilators.*',
                'patients.patient_code AS patient_code'
            ])
            ->where('ventilators.id', $id)
            ->orderBy('ventilators.created_at', 'DESC')
            ->value('patient_code');
    }

    private static function queryBySearchValues(array $search_values)
    {
        $query = self::leftJoinOrganization();
        $query = self::leftJoinUser($query);
        $query = self::leftJoinVentilatorBug($query);
        return self::createWhereClauseFromSearchValues($query, $search_values);
    }

    private static function queryByOrganizationIdAndSearchValues($organization_id, array $search_values)
    {
        $query = self::leftJoinUser(self::queryByOrganizationId($organization_id));
        $query = self::leftJoinVentilatorBug($query);
        return self::createWhereClauseFromSearchValues($query, $search_values);
    }

    public static function findBySearchValuesAndOffsetAndLimit($offset, $limit, $search_values)
    {
        return self::queryBySearchValues($search_values)
            ->select([
                'ventilators.*',
                'organizations.name AS organization_name',
                'users.name AS registered_user_name',
                'ventilator_bugs.ventilator_id AS bug_ventialtor_id'
            ])
            ->distinct()
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->orderBy('gs1_code', 'ASC')
            ->get();
    }

    public static function findByOrganizationIdAndSearchValuesAndOffsetAndLimit($organization_id, $search_values, $offset, $limit)
    {
        return self::queryByOrganizationIdAndSearchValues($organization_id, $search_values)
            ->select([
                'ventilators.*',
                'users.name AS registered_user_name',
                'ventilator_bugs.ventilator_id AS bug_ventialtor_id'
            ])
            ->distinct()
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->orderBy('gs1_code', 'ASC')
            ->get();
    }

    public static function countBySearchValues(array $search_values)
    {
        return self::queryBySearchValues($search_values)
            ->select([
                'ventilators.*',
                'organizations.name AS organization_name',
                'users.name AS registered_user_name',
                'ventilator_bugs.ventilator_id AS bug_ventialtor_id'
            ])
            ->distinct()
            ->count();
    }

    public static function countByOrganizationIdAndSearchValues($organizaiton_id, array $search_values)
    {
        return self::queryByOrganizationIdAndSearchValues($organizaiton_id, $search_values)
            ->select([
                'ventilators.*',
                'users.name AS registered_user_name',
                'ventilator_bugs.ventilator_id AS bug_ventialtor_id'
            ])
            ->distinct()
            ->count();
    }

    private static function createWhereClauseFromSearchValues($query, $search_values)
    {
        if (isset($search_values['serial_number'])) {
            $serial_number = $search_values['serial_number'];
            $query->where('ventilators.serial_number', $serial_number);
        }
        if (isset($search_values['organization_id'])) {
            $organization_id = $search_values['organization_id'];
            $query->where('ventilators.organization_id', $organization_id);
        }
        if (isset($search_values['registered_user_name'])) {
            $registered_users_name = $search_values['registered_user_name'];
            $query->where('users.name', 'like', "%$registered_users_name%");
        }
        if (isset($search_values['expiration_date_from'])) {
            $query->where('ventilators.expiration_date', '>=', $search_values['expiration_date_from']);
        }
        if (isset($search_values['expiration_date_to'])) {
            $query->where('ventilators.expiration_date', '<=', $search_values['expiration_date_to']);
        }
        if (isset($search_values['start_using_at_from'])) {
            $query->where('ventilators.start_using_at', '>=', $search_values['start_using_at_from']);
        }
        if (isset($search_values['start_using_at_to'])) {
            $query->where('ventilators.start_using_at', '<=', $search_values['start_using_at_to']);
        }
        if (isset($search_values['has_bug']) && count($search_values['has_bug']) === 1) {
            $search_values['has_bug'][0] ? $query->whereNotNull('ventilator_bugs.ventilator_id') : $query->whereNull('ventilator_bugs.ventilator_id');
        }

        return $query;
    }

    private static function leftjoinVentilatorValue($query = null)
    {
        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                'ventilator_values',
                'ventilator_values.ventilator_id',
                '=',
                'ventilators.id'
            );
    }

    private static function joinPatientAndPatientValue($query = null)
    {
        return static::leftJoinPatient($query)
            ->leftjoin(
                'patient_values',
                'patient_values.patient_id',
                '=',
                'patients.id'
            );
    }

    public static function queryWithVentilatorValuesAndPatientsAndPatientValuesByids($ids)
    {
        $query = static::leftjoinVentilatorValue();
        $query = static::joinPatientAndPatientValue($query);
        return $query
            ->select(
                'ventilators.*',
                'patients.*',
                'patient_values.*',
                'ventilator_values.*',
                'ventilators.id AS ventilator_id',
                'patients.id AS patient_id',
                'patient_values.id AS patient_value_id',
                'ventilator_values.id AS ventilator_value_id',
                'patients.height AS patient_height',
                'patients.weight AS patient_weight',
                'patients.gender AS patient_gender',
                'ventilator_values.height AS height',
                'ventilator_values.weight AS weight',
                'ventilator_values.gender AS gender',
                'patient_values.registered_at AS patient_value_registered_at',
                'ventilator_values.registered_at AS ventilator_value_registered_at',
            )
            ->whereIn('ventilators.id', $ids);
    }

    public static function updateBulkForPatientId(
        array $ventilator_ids,
        array $ventilator_patient_ids
    ) {
        $table = Ventilator::tableName();

        //動的パラメータ置換用placeholder ?,?,?,...
        $placeholder = substr(str_repeat(',?', count($ventilator_ids)), 1);

        $query = <<< EOM
            UPDATE
                {$table}
            SET
                patient_id = ELT(FIELD(id,{$placeholder}),{$placeholder})
            WHERE
                id IN ({$placeholder})
        EOM;

        \DB::update($query, array_merge($ventilator_ids, $ventilator_patient_ids, $ventilator_ids));
    }

    public static function findOneByOrganizationIdAndId($organization_id, $id)
    {
        return self::queryByOrganizationId($organization_id)->where('id', $id)->first();
    }

    public static function logicalDeleteByOrganizationIdAndIds($organization_id, $ids)
    {
        return self::queryByOrganizationId($organization_id)->whereIn('id', $ids)->update(['deleted_at' => DateUtil::now(), 'active' => null]);
    }

    public static function getPatientCodeByOrganizationIdAndId($organization_id, $id)
    {
        return static::leftJoinPatient(self::queryByOrganizationId($organization_id))
            ->addSelect([
                'ventilators.*',
                'patients.patient_code AS patient_code'
            ])
            ->where('ventilators.id', $id)
            ->orderBy('ventilators.created_at', 'DESC')
            ->value('patient_code');
    }

}
