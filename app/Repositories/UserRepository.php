<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;
use App\Services\Support;

class UserRepository
{
    private static function query()
    {
        return User::query();
    }

    public static function existsByNameAndOrganizationId(string $name, int $organization_id)
    {
        return static::query()->where('name', $name)->where('organization_id', $organization_id)->exists();
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByToken(string $token)
    {
        return static::query()->where(User::TOKEN_COLUMN_NAME, $token)->first();
    }

    public static function findOneByOrganizationIdAndName(int $organization_id, string $name)
    {
        return static::query()->where('organization_id', $organization_id)->where('name', $name)->first();
    }

    public static function findOneByOrganizationIdAndId(int $organization_id, int $id)
    {
        return static::query()->where('organization_id', $organization_id)->where('id', $id)->first();
    }

    public static function findOneByAuthorityAndId(int $authority, int $id)
    {
        return static::query()->where('authority', $authority)->where('id', $id)->first();
    }
    
    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id', $id)->value('organization_id');
    }

    public static function getOrganizationIdsByIds(array $ids) 
    {
        return static::query()->whereIn('id', $ids)->pluck('organization_id');
    }
    
    public static function findOneWithOrganizationByAuthorityAndId(int $authority, int $id)
    {
        $table = User::tableName();
        $query = self::joinOrganization(static::query());
        return $query->where($table . '.authority', $authority)->where($table . '.id', $id)->first();
    }

    public static function findWithOrganizationByAuthorityAndSearchValuesAndLimitAndOffsetOrderByCreatedAt(
        int $authority,
        array $search_values,
        int $limit,
        int $offset
    ) {
        return self::queryByAuthorityAndSeachValuesOrderByCreatedAt($authority, $search_values)
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function logicalDeleteByIds(array $ids, int $updated_user_id)
    {
        return  static::query()
            ->whereIn('id', $ids)
            ->update([
                'updated_user_id' => $updated_user_id, 
                'deleted_at' => Support\DateUtil::now()
            ]);
    }

    public static function countByAuthorityAndSearchValues(int $authority, array $search_values)
    {
        $query = self::joinOrganization(static::query());
        return static::createWhereClauseFromAuthorityAndSearchValues(
            $query,
            $authority,
            $search_values
        )->count();
    }

    private static function queryByAuthorityAndSeachValuesOrderByCreatedAt(int $authority, array $search_values)
    {
        $query = self::joinOrganization(static::query());
        return self::createWhereClauseFromAuthorityAndSearchValues(
            $query,
            $authority,
            $search_values
        )->orderBy('created_at', 'DESC');
    }

    private static function joinOrganization($query)
    {
        $table = User::tableName();
        $organization_table = Organization::tableName();

        $query->join($organization_table, $table . '.organization_id', '=', $organization_table . '.id');
        $query->addSelect([
            $table . '.*',
            $organization_table . '.name AS organization_name',
            $organization_table . '.code',
        ]);

        return $query;
    }

    private static function createWhereClauseFromAuthorityAndSearchValues(
        $query,
        int $authority,
        array $search_values
    ) {
        $table = User::tableName();
        $organization_table = Organization::tableName();

        $query->where($table . '.authority', $authority);

        if (isset($search_values['organization_name'])) {
            $query->where($organization_table . '.name', $search_values['organization_name']);
        }
        if (isset($search_values['name'])) {
            $name = $search_values['name'];
            $query->where($table . '.name', 'like', "%$name%");
        }
        if (isset($search_values['registered_at_from'])) {
            $query->where($table . '.created_at', '>=', $search_values['registered_at_from']);
        }
        if (isset($search_values['registered_at_to'])) {
            $query->where($table . '.created_at', '<=', $search_values['registered_at_to']);
        }
        if (isset($search_values['disabled_flg'])) {
            $query->whereIn($table . '.disabled_flg', $search_values['disabled_flg']);
        }

        return $query;
    }

    public static function findByOrganizationId(int $organization_id)
    {
        return static::query()->where('organization_id', $organization_id)->get();
    }

    public static function search(
        array $search_values, 
        int $limit, 
        int $offset)
    {
        $query = static::query();

        return self::createWhereClauseFormSearchValues($query, $search_values)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function countBySearchValues(array $search_values)
    {
        $query = static::query();

        return self::createWhereClauseFormSearchValues($query, $search_values)
            ->count();
    }

    private static function createWhereClauseFormSearchValues($query, array $search_values)
    {
        $organization_id = $search_values['organization_id'];
        $query->where('users.organization_id', $organization_id);

        if (isset($search_values['name'])){
            $name = $search_values['name'];
            $query->where('users.name', 'like', "%$name%");
        }

        if (isset($search_values['authority'])){
            $authority = $search_values['authority'];
            $query->where('users.authority', $authority);
        }

        if (isset($search_values['registered_at_from'])){
            $registered_at_from = $search_values['registered_at_from'];
            $query->where('users.created_at', '>=', $registered_at_from);
        }

        if (isset($search_values['registered_at_to'])){
            $registered_at_to = $search_values['registered_at_to'];
            $query->where('users.created_at', '<=', $registered_at_to);
        }

        if (isset($search_values['disabled_flg'])) {
            $disabled_flg = $search_values['disabled_flg'];
            $query->whereIn('users.disabled_flg', $disabled_flg);
        }

        return $query;
    }
}
