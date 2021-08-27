<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientValue extends TraceableBaseModel
{
    const
    UPDATED_AT = null;

    // 使用場所
    const
    // 救急車+周囲
    AMBULANCE = 1,
    // 救命救急室
    EMERGENCY_ROOM = 2,
    // ICU
    ICU = 3,
    // 手術室
    OPERATING_ROOM = 4,
    // MRI室
    MRI = 5,
    // その他の検査室
    OTHER_LABORATORIES = 6,
    // ICU以外の病室
    NON_ICU = 7,
    // その他の場所
    OTHER_PLACES = 8;

    // 使用中止時の転帰
    const
    // 改善
    IMPROVEMENT = 1,
    // 不変　
    IMMUTABLE = 2,
    // 悪化
    DETERIORATION = 3,
    // 死亡
    DEATH = 4;

    // 使用中止後の呼吸不全治療
    const
    // なし
    NONE = 1,
    // 酸素投与のみ
    OXYGEN_ONLY = 2,
    // NPPVに変更
    NPPV = 3,
    // 他の人工呼吸器に変更
    OTHER_VENTILATOR = 4,
    // ECMO
    ECMO = 5,
    // その他
    OTHER = 6;
}
