<?php

namespace App\Services\Support\Logic;

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
trait OrganizationCheckLogic
{
    /**
     * ユーザーと呼吸器の組織整合がとれているかどうか
     *
     * @param $user_id
     * @param $ventilator_id
     * @return boolean
     */
    public function checkUserAgainstVentilator($user_id, $ventilator_id)
    {
        $u_org_id = Repos\UserRepository::getOrganizationIdById($user_id);
        $v_org_id = Repos\VentilatorRepository::getOrganizationIdById($ventilator_id);

        $organization_check = is_null($v_org_id) || $v_org_id === $u_org_id;

        return $organization_check;
    }

    /**
     * ユーザーと患者の組織整合がとれているかどうか
     *
     * @param $user_id
     * @param $patient_id
     * @return boolean
     */
    public function checkUserAgainstPatient($user_id, $patient_id)
    {
        $u_org_id = Repos\UserRepository::getOrganizationIdById($user_id);
        $p_org_id = Repos\PatientRepository::getOrganizationIdById($patient_id);

        $organization_check = is_null($p_org_id) || $p_org_id === $u_org_id;

        return $organization_check;
    }
}
