<?php

namespace App\Services\Support;

use App\Exceptions;

use App\Models;
use App\Repositories as Repos;
use App\Http\Forms as Form;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Http\Response\Api as Response;
use App\Models\User;
use Closure;

/**
 * 組織整合性を調べるためのトレイト
 */
class OrganizationCheckUtil
{
    /**
     * ユーザーと呼吸器の組織整合がとれているかどうか
     *
     * @param User $user
     * @param $ventilator_id
     * @return boolean
     */
    public static function checkUserAgainstVentilator(User $user, $ventilator_id)
    {
        $u_org_id = $user->organization_id;
        $v_org_id = Repos\VentilatorRepository::getOrganizationIdById($ventilator_id);

        $organization_check = is_null($v_org_id) || $v_org_id === $u_org_id;

        return $organization_check;
    }

    /**
     * ユーザーと患者の組織整合がとれているかどうか
     *
     * @param User $user
     * @param $patient_id
     * @return boolean
     */
    public function checkUserAgainstPatient(User $user, $patient_id)
    {
        $u_org_id = $user->organization_id;
        $p_org_id = Repos\PatientRepository::getOrganizationIdById($patient_id);

        $organization_check = is_null($p_org_id) || $p_org_id === $u_org_id;

        return $organization_check;
    }
}
