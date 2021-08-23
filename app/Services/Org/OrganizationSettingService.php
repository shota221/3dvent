<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Repositories as Repos;
use App\Http\Forms\Org as Form;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Http\Response;

class OrganizationSettingService
{
    /**
     * 組織設定取得
     *
     * @return [type]
     */
    public function getOrganizationSettingData()
    {
        // TODO Auth::user->organization_idで取得
        $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId(1);

        return Converter\OrganizationSettingConverter::convertToSettingResult($organization_setting);
    }

    /**
     * 組織設定更新
     *
     * @param Form\OrganizationSettingUpdateForm $form
     * @return [type]
     */
    public function update(Form\OrganizationSettingUpdateForm $form)
    {
        // TODO Auth::user->organization_idで取得
        $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId(1);
 
        $entity = Converter\OrganizationSettingConverter::convertToUpdateEntity(
            $organization_setting, 
            $form->ventilator_value_scan_interval,
            $form->vt_per_kg
        );

        DBUtil::Transaction(
            '組織設定値更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }
}