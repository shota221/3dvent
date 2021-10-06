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

    public static function findByOrganizationId(int $organization_id)
    {
        return static::query()->where('organization_id', $organization_id)->get();
    }
    
    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id', $id)->value('organization_id');
    }

    public static function getIdsByOrganizationIdAndIds(int $organization_id, array $ids) 
    {
        return static::query()->where('organization_id', $organization_id)->whereIn('id', $ids)->pluck('id');
    }
    
    public static function findOneWithOrganizationByAuthorityAndId(int $authority, int $id)
    {
        $table = User::tableName();
        $query = self::joinOrganization(static::query());
        return $query->where($table . '.authority', $authority)->where($table . '.id', $id)->first();
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

    public static function searchByAuthority(
        array $search_values,
        int $authority,
        int $limit,
        int $offset
    ) {
        return self::queryWithOrganizationByAuthorityAndSeachValuesOrderByCreatedAt($authority, $search_values)
            ->limit($limit)
            ->offset($offset)
            ->get();
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

    private static function queryWithOrganizationByAuthorityAndSeachValuesOrderByCreatedAt(int $authority, array $search_values)
    {
        $query = self::joinOrganization(static::query());
        $query = self::createWhereClauseFromAuthority($query, $authority);
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

        $query->join('organizations', 'users.organization_id', '=', 'organizations.id');
        $query->select([
            'users.*',
            'organizations.name AS organization_name',
            'organizations.code',
        ]);

        return $query;
    }
   
    private static function createWhereClauseFromAuthority($query, int $authority)
    {
        return $query->where('users.authority', $authority);
    }

    private static function createWhereClauseFromAuthorityAndSearchValues(
        $query,
        int $authority,
        array $search_values
    ) {

        $query->where('users.authority', $authority);

        if (isset($search_values['organization_id'])) {
            $query->where('users.organization_id', $search_values['organization_id']);
        }
        if (isset($search_values['name'])) {
            $name = $search_values['name'];
            $query->where('users.name', 'like', "%$name%");
        }
        if (isset($search_values['registered_at_from'])) {
            $query->where('users.created_at', '>=', $search_values['registered_at_from']);
        }
        if (isset($search_values['registered_at_to'])) {
            $query->where('users.created_at', '<=', $search_values['registered_at_to']);
        }
        if (isset($search_values['disabled_flg'])) {
            $query->where('users.disabled_flg', $search_values['disabled_flg']);
        }

        return $query;
    }

    public static function searchByOrganizationId(
        array $search_values, 
        int $organization_id,
        int $limit, 
        int $offset)
    {
        $query = self::createWhereClauseFromOrganizationId(static::query(), $organization_id);

        return self::createWhereClauseFromSearchValues($query, $search_values)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function countByOrganizationIdAndSearchValues(int $organization_id, array $search_values)
    {
        $query = self::createWhereClauseFromOrganizationId(static::query(), $organization_id);

        return self::createWhereClauseFromSearchValues($query, $search_values)
            ->count();
    }

    private static function createWhereClauseFromOrganizationId($query, int $organization_id)
    {
        return $query->where('users.organization_id', $organization_id);
    }

    private static function createWhereClauseFromSearchValues($query, array $search_values)
    {

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
            $query->where('users.disabled_flg', $disabled_flg);
        }

        return $query;
    }
}
