<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Patient;
use App\Models\User;
use App\Models\Ventilator;
use App\Models\VentilatorBug;

class VentilatorRepository
{
    private static function query()
    {
        return Ventilator::query();
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

    public static function findOneByGs1Code($gs1_code)
    {
        $table = Ventilator::tableName();
        return static::leftJoinOrganization()->where('gs1_code', $gs1_code)->orderBy($table . '.created_at', 'DESC')->first();
    }

    private static function leftJoinOrganization($query = null)
    {
        $table = Ventilator::tableName();

        $organization_table = Organization::tableName();

        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                $organization_table,
                function ($join) use ($table, $organization_table) {
                    $join
                        ->on($organization_table . '.id', '=', $table . '.organization_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $organization_table . '.name AS organization_name',
                $organization_table . '.code AS organization_code'
            ]);
    }

    private static function leftJoinUser($query = null)
    {
        $table = Ventilator::tableName();

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

    private static function leftJoinVentilatorBug($query = null)
    {
        $table = Ventilator::tableName();

        $ventilator_bug_table = VentilatorBug::tableName();

        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                $ventilator_bug_table,
                function ($join) use ($table, $ventilator_bug_table) {
                    $join
                        ->on($ventilator_bug_table . '.ventilator_id', '=', $table . '.id');
                }
            )
            ->addSelect([
                $table . '.*',
                $ventilator_bug_table . '.ventilator_id AS has_bug',
            ]);
    }

    private static function leftJoinPatient($query = null)
    {
        $table = Ventilator::tableName();

        $patient_table = Patient::tableName();

        return (!is_null($query) ? $query : static::query())
            ->leftJoin(
                $patient_table,
                function ($join) use ($table, $patient_table) {
                    $join
                        ->on($patient_table . '.id', '=', $table . '.patient_id');
                }
            )
            ->addSelect([
                $table . '.*',
                $patient_table . '.patient_code AS patient_code'
            ]);
    }

    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id', $id)->value('organization_id');
    }

    public static function getPatientCodeById(int $id)
    {
        $table = Ventilator::tableName();
        return static::leftJoinPatient()->where($table.'.id',$id)->orderBy($table.'.created_at', 'DESC')->value('patient_code');
    }

    public static function findBySearchValuesAndOffsetAndLimit($offset, $limit, $search_values)
    {
        return self::createWhereClauseFromSearchValues(static::leftJoinVentilatorBug(static::leftJoinUser(static::leftJoinOrganization())), $search_values)
            ->distinct()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function countBySearchValues($search_values)
    {
        return self::createWhereClauseFromSearchValues(static::query(), $search_values)->count();
    }

    private static function createWhereClauseFromSearchValues($query, $search_values)
    {
        if (isset($search_values['serial_number'])) {
            $serial_number = $search_values['serial_number'];
            $query->where('name', $serial_number);
        }
        if (isset($search_values['organization_name'])) {
            $organization_name = $search_values['organization_name'];
            $query->where('organization_name', 'like', "%$organization_name%");
        }
        if (isset($search_values['registered_user_name'])) {
            $registered_user_name = $search_values['registered_user_name'];
            $query->where('registered_user_name', 'like', "%$registered_user_name%");
        }
        if (isset($search_values['expiration_date_from'])) {
            $query->where('expiration_date', '>=', $search_values['expiration_date_from']);
        }
        if (isset($search_values['expiration_date_to'])) {
            $query->where('expiration_date', '<=', $search_values['expiration_date_to']);
        }
        if (isset($search_values['start_using_at_from'])) {
            $query->where('start_using_at', '>=', $search_values['start_using_at_from']);
        }
        if (isset($search_values['start_using_at_to'])) {
            $query->where('start_using_at', '<=', $search_values['start_using_at_to']);
        }
        if (isset($search_values['has_bug'])) {
            $search_values['has_bug'] ? $query->whereNotNull('has_bug') : $query->whereNull('has_bug');
        }

        return $query;
    }
}
