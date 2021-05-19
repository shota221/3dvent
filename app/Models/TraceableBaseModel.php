<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 該当テーブルに挿入・論理削除が走った際に
 * そのidとoperation(create/delete)を記録するヒストリーテーブルが存在する場合に継承。
 */
class TraceableBaseModel extends BaseModel
{

    /**
     * ヒストリテーブル名取得
     * 
     * @return [type] [description]
     */
    public static function historyTableName()
    {
        return Str::snake(class_basename(static::class)) . '_histories';
    }

    /**
     * ヒストリテーブルモデル名取得
     * 
     * @return [type] [description]
     */
    public static function historyModelBaseName()
    {
        return class_basename(static::class) . 'History';
    }

    /**
     * 対応するヒストリテーブルのidカラム名を取得
     * @return void
     */
    public static function targetColumnOfHistoryTable()
    {
        return Str::snake(class_basename(static::class)) . '_id';
    }
}
