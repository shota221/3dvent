<?php 

namespace App\Services\Support\Converter\NameConverter;

use App\Http\Response as Response;
use App\Models;

class PatientValueConverter
{
    public static function convertToUsedPlaceName(int $used_place = null)
    {
        switch ($used_place) {
            case Models\PatientValue::AMBULANCE:
                return __('messages.ambulance');
                break;
            case Models\PatientValue::EMERGENCY_ROOM:
                return __('messages.emergency_room');
                break;
            case Models\PatientValue::ICU:
                return __('messages.icu');
                break;
            case Models\PatientValue::OPERATING_ROOM:
                return __('messages.operating_room');
                break;
            case Models\PatientValue::MRI:
                return __('messages.mri');
                break;
            case Models\PatientValue::OTHER_LABORATORIES:
                return __('messages.other_laboratories');
                break;
            case Models\PatientValue::NON_ICU:
                return __('messages.non_icu');
                break;
            case Models\PatientValue::OTHER_PLACES:
                return __('messages.other_places');
                break;
            default:
                return '';
        }
    }

    public static function convertToOutcomeName(int $outcome = null)
    {
        switch ($outcome) {
            case Models\PatientValue::IMPROVEMENT:
                return __('messages.improvement');
                break;
            case Models\PatientValue::IMMUTABLE:
                return __('messages.immutable');
                break;
            case Models\PatientValue::DETERIORATION:
                return __('messages.deterioration');
                break;
            case Models\PatientValue::DEATH:
                return __('messages.death');
                break;
            default:
                return '';
        }
    }
    
    public static function convertToTreatmentName(int $treatment = null)
    {
        switch ($treatment) {
            case Models\PatientValue::NONE:
                return __('messages.none');
                break;
            case Models\PatientValue::OXYGEN_ONLY:
                return __('messages.oxygen_only');
                break;
            case Models\PatientValue::NPPV:
                return __('messages.nppv');
                break;
            case Models\PatientValue::OTHER_VENTILATOR:
                return __('messages.other_ventilator');
                break;
            case Models\PatientValue::ECMO:
                return __('messages.ecmo');
                break;
            case Models\PatientValue::OTHER:
                return __('messages.other');
                break;
            default:
                return '';
        }
    }
}