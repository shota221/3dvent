<?php

namespace App\Services\Support;

use App\Exceptions;
use App\Models\TraceableBaseModel;
use App\Services\Support\Converter;

/**
 * 機器観察研究データや観察研究データ等の
 * CRUD処理は各historyテーブルに記録される。
 * ユーザによる更新操作は
 * DB上ではインサートデリート（論理削除）とする。
 */
class HistoryUtil
{
    const CREATE = 1;
    const DELETE = 2;

    public static function create(TraceableBaseModel $entity, $operated_user_id)
    {
        $history = Converter\HistoryConverter::convertToHistoryEntity($entity, self::CREATE, $operated_user_id);
        
        DBUtil::Transaction(
            $entity->tableName().'レコード作成履歴の登録',
            function () use ($history) {
                $history->save();
            }
        );

        return $history;
    }

    public static function delete(TraceableBaseModel $entity, $operated_user_id)
    {
        $history = Converter\HistoryConverter::convertToHistoryEntity($entity, self::DELETE, $operated_user_id);

        DBUtil::Transaction(
            $entity->tableName().'レコード削除履歴の登録',
            function () use ($history) {
                $history->save();
            }
        );

        return $history;
    }
}
