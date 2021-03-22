<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BaseModel extends EloquentModel
{

    /**
     * テーブル名取得
     * 
     * EloquentModel::getTable()と同様の実装
     * 変更する場合はoverride
     * 
     * @return [type] [description]
     */
    public static function tableName()
    {
        return Str::snake(Str::pluralStudly(class_basename(static::class)));
    }

    const
        BOOLEAN_TRUE = 1,
        BOOLEAN_FALSE = 0
        ;
}
