<?php

namespace App\Repositories;

use App\Models\Organization;

class OrganizationRepository
{
    private static function query()
    {
        return Organization::query();
    }

    public static function count($query = null)
    {
        return !is_null($query) ? $query->count() : static::query()->count();
    }

    public static function existsById(int $id)
    {
        return static::query()->where('id',$id)->exists();
    }

    public static function existsByCode(string $code)
    {
        return static::query()->where('code', $code)->exists();
    }

    public static function existsByRepresentativeEmail(string $representative_email)
    {
        return static::query()->where('representative_email', $representative_email)->exists();
    }


    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByCode(string $code)
    {
        return static::query()->where('code', $code)->first();
    }

    public static function findBySearchValuesAndOffsetAndLimit($offset, $limit, $search_values)
    {
        return self::createWhereClauseFromSearchValues(static::query(), $search_values)
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->orderBy('code','ASC')
            ->get();
    }

    public static function findAll()
    {
        return static::query()->get();
    }

    public static function countBySearchValues($search_values)
    {
        return self::createWhereClauseFromSearchValues(static::query(), $search_values)->count();
    }

    private static function createWhereClauseFromSearchValues($query, $search_values)
    {
        if (isset($search_values['organization_name'])) {
            $organization_name = $search_values['organization_name'];
            $query->where('name','like',"%$organization_name%");
        }
        if (isset($search_values['representative_name'])) {
            $representative_name = $search_values['representative_name'];
            $query->where('representative_name','like',"%$representative_name%");
        }
        if (isset($search_values['organization_code'])) {
            $query->where('code',$search_values['organization_code']);
        }
        if (isset($search_values['disabled_flg'])) {
            $query->whereIn('disabled_flg',$search_values['disabled_flg']);
        }
        if (isset($search_values['edc_linked_flg']) && count($search_values['edc_linked_flg'])===1) {
            $search_values['edc_linked_flg'][0] ? $query->whereNotNull('edcid') : $query->whereNull('edcid');
        }
        if (isset($search_values['patient_obs_approved_flg'])) {
            $query->whereIn('patient_obs_approved_flg',$search_values['patient_obs_approved_flg']);
        }
        if (isset($search_values['registered_at_from'])) {
            $query->where('created_at','>=',$search_values['registered_at_from']);
        }
        if (isset($search_values['registered_at_to'])) {
            $query->where('created_at','<=',$search_values['registered_at_to']);
        }

        return $query;
    }
}
