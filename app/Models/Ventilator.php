<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ventilator extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * 備考：ventilatorの"状態"について
     * ventilatorには以下の3つの状態がある。
     * また、ventilatorの状態変化に伴い、紐づくpatientの状態も変化する。
     * 
     *  状態名           | active  | deleted_at | 説明
     * 1.活性(デフォルト) | 1       | NULL       | 以下の2でも3でもない状態。ユーザーが権限を満たしている限り、どの端末からも閲覧可能
     * 2.非活性          | NULL    | NULL       | 1つのgs1_codeに対して複数のpatientを紐付ける際（microventの多数回利用時）にスマートフォンアプリから"初期化"が行われた状態。スマートフォンアプリからの閲覧不可。
     * 3.削除            | NULL    | NOT NULL   |組織移動等の際にadmin管理画面から"削除"が行われた状態。どの端末からも閲覧不可。
     * 
     * すなわち、activeはスマートフォンアプリからの閲覧に係る値であり、deleted_atは管理画面からの閲覧に係る値である。
     */
    const ACTIVE = 1,
        INACTIVE = null;

    public function setLocationAttribute(array $latlng)
    {
        $this->attributes['location'] = \DB::raw("(ST_GeomFromText('POINT(" . $latlng['lng'] . " " . $latlng['lat'] . ")'))");
    }

    public function getLatAttribute()
    {
        return $this->attributes['lat'];
    }

    public function getLngAttribute()
    {
        return $this->attributes['lng'];
    }
}
