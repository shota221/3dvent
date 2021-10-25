<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth\AppkeyGate;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

use function PHPUnit\Framework\isJson;

class BugReportService
{
    public function create($form, $user = null)
    {
        $ventilator_id = $form->ventilator_id;

        $ventilator_exists = Repos\VentilatorRepository::existsById($ventilator_id);

        if (!$ventilator_exists) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        //AppkeyGate通過済みであるためnot null
        $appkey = AppkeyGate::getValidAppkey();

        $appkey_id = $appkey->id;

        $bug_registered_user_id = null;

        if (!is_null($user)) {
            $bug_registered_user_id = $user->id;
        }

        $registered_at = DateUtil::toDatetimeStr(DateUtil::now());

        $entity = Converter\BugReportConverter::convertToEntity(
            $ventilator_id,
            $form->bug_name,
            $form->request_improvement,
            $registered_at,
            $appkey_id,
            $bug_registered_user_id
        );

        DBUtil::Transaction(
            '不具合報告登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\BugReportConverter::convertToBugReportRegistrationResult($ventilator_id);
    }
}
