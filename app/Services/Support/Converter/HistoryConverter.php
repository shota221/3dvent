<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;
use App\Models\TraceableBaseModel;
use App\Services\Support\DateUtil;

class HistoryConverter
{
    public static function convertToHistoryEntity(TraceableBaseModel $target,int $operation, $operated_user_id)
    {
        $history_model = 'App\\Models\\'.$target->historyModelBaseName();

        $entity = new $history_model();

        $entity->{$target->targetColumnOfHistoryTable()} = $target->id;

        $entity->operation = $operation;

        $entity->operated_user_id = $operated_user_id;

        return $entity;
    }
}
