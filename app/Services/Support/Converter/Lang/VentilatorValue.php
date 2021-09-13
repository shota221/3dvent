<?php

namespace App\Services\Support\Converter\Lang;

use App\Http\Response as Response;
use App\Models;

class VentilatorValue
{
    public static function convertToGenderName(int $gender = null)
    {
        switch ($gender) {
            case Models\VentilatorValue::MALE:
                return __('messages.male');
                break;
            case Models\VentilatorValue::FEMALE:
                return __('messages.female');
                break;
            default:
                return '';
        }
    }

    public static function convertToStatusUseName(int $status_use = null)
    {
        switch ($status_use) {
            case Models\VentilatorValue::RESPIRATORY_FAILURE:
                return __('messages.respiratory_failure');
                break;
            case Models\VentilatorValue::SURGERY:
                return __('messages.surgery');
                break;
            case Models\VentilatorValue::INSPECTION_PROCEDURE:
                return __('messages.inspection_procedure');
                break;
            case Models\VentilatorValue::STATUS_USE_OTHER:
                return __('messages.other');
                break;
            default:
                return '';
        }
    }
}
