<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * 備考：patientの"状態"について
     * patientには以下の3つの状態がある。
     * また、patientの状態変化は、ventilatorの状態変化に従う。
     * 
     *  状態名           | active  | deleted_at | 説明
     * 1.活性(デフォルト) | 1       | NULL       | 以下の2でも3でもない状態。ユーザーが権限を満たしている限り、どの端末からも閲覧可能
     * 2.非活性          | NULL    | NULL       | 1つのgs1_codeに対して複数のpatientを紐付ける際（microventの多数回利用時）にスマートフォンアプリから"初期化"が行われた状態。スマートフォンアプリからの閲覧不可。
     * 3.削除            | NULL    | NOT NULL   |組織移動等の際にadmin管理画面から"削除"が行われた状態。どの端末からも閲覧不可。
     * 
     */
    const ACTIVE = 1,
        INACTIVE = null;
}
