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


    public function getUsedPlaceName()
    {
        switch ($this->used_place) {
            case self::AMBULANCE:
                return __('messages.ambulance');
                break;
            case self::EMERGENCY_ROOM:
                return __('messages.emergency_room');
                break;
            case self::ICU:
                return __('messages.icu');
                break;
            case self::OPERATING_ROOM:
                return __('messages.operating_room');
                break;
            case self::MRI:
                return __('messages.mri');
                break;
            case self::OTHER_LABORATORIES:
                return __('messages.other_laboratories');
                break;
            case self::NON_ICU:
                return __('messages.non_icu');
                break;
            case self::OTHER_PLACES:
                return __('messages.other_places');
                break;
            default:
                return '';
        }
    }

    public function getOutcomeName()
    {
        switch ($this->outcome) {
            case self::IMPROVEMENT:
                return __('messages.improvement');
                break;
            case self::IMMUTABLE:
                return __('messages.immutable');
                break;
            case self::DETERIORATION:
                return __('messages.deterioration');
                break;
            case self::DEATH:
                return __('messages.death');
                break;
            default:
                return '';
        }
    }

    public function getTreatmentName()
    {
        switch ($this->treatment) {
            case self::NONE:
                return __('messages.none');
                break;
            case self::OXYGEN_ONLY:
                return __('messages.oxygen_only');
                break;
            case self::NPPV:
                return __('messages.nppv');
                break;
            case self::OTHER_VENTILATOR:
                return __('messages.other_ventilator');
                break;
            case self::ECMO:
                return __('messages.ecmo');
                break;
            case self::OTHER:
                return __('messages.other');
                break;
            default:
                return '';
        }
    }
}
