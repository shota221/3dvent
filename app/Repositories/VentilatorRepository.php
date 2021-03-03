<?php

namespace App\Repositories;

use App\Models\Ventilator;

use App\Models\Organization;

class VentilatorRepository
{
    private static function query()
    {
        return Ventilator::query();
    }

    public static function existsByGs1Code($gs1_code)
    {
        return static::query()->where('gs1_code', $gs1_code)->exists();
    }

    public static function findOneByGs1Code($gs1_code)
    {
        $table = Ventilator::tableName();
        return static::leftJoinOrganization()->where('gs1_code', $gs1_code)->orderBy($table.'.created_at', 'DESC')->first();
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
            ]);
    }
}
