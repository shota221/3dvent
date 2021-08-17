<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;

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

    public static function findOneByAuthorityAndId(int $authority, int $id)
    {
        return static::query()->where('authority', $authority)->where('id', $id)->first();
    }

    
    public static function getOrganizationIdById(int $id)
    {
        return static::query()->where('id',$id)->value('organization_id');
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
        int $offset )
    {
        return self::queryByAuthorityAndSeachValuesOrderByCreatedAt($authority, $search_values)
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
            $search_values)->count();
    }

    private static function queryByAuthorityAndSeachValuesOrderByCreatedAt(int $authority, array $search_values)
    {
        $query = self::joinOrganization(static::query());
        return self::createWhereClauseFromAuthorityAndSearchValues(
            $query, 
            $authority, 
            $search_values)->orderBy('created_at', 'DESC');
    }

    private static function joinOrganization($query)
    {
        $table = User::tableName();
        $organization_table = Organization::tableName();

        $query->join($organization_table, $table . '.organization_id', '=' , $organization_table . '.id');
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
        array $search_values)
    {
        $table = User::tableName();
        $organization_table = Organization::tableName();

        $query->where($table . '.authority', $authority);

        if(isset($search_values['organization_name'])){
            $query->where($organization_table . '.name', $search_values['organization_name']);
        }
        if(isset($search_values['name'])){
            $name = $search_values['name'];
            $query->where($table . '.name','like',"%$name%");
        }
        if(isset($search_values['registered_at_from'])){
            $query->where($table . '.created_at','>=',$search_values['registered_at_from']);
        }
        if(isset($search_values['registered_at_to'])){
            $query->where($table . '.created_at','<=',$search_values['registered_at_to']);
        }

        return $query;

    }
}
