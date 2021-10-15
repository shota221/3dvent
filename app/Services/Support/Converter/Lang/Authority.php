<?php 

namespace App\Services\Support\Converter\Lang;

use App\Models;

class Authority 
{
    public static function convertToAuthorityName(int $authority_type)
    {
        switch ($authority_type) {
            case Models\User::ADMIN_PROJETCT_MANAGER_TYPE:
                return __('messages.project_manager');
                break;
            case Models\User::ADMIN_DATA_MANAGER_TYPE:
                return __('messages.data_manager');
                break;
            case Models\User::ADMIN_DATA_MONITOR_TYPE:
                return __('messages.data_monitor');
                break;
            case Models\User::ADMIN_PRINCIPAL_INVESTIGATOR_TYPE:
                return __('messages.overall_principal_investigator');
                break;
            case Models\User::ADMIN_COMPANY_TYPE:
                return __('messages.company');
                break;
            case Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE:
                return __('messages.principal_investigator');
                break;
            case Models\User::ORG_OTHRE_INVESTIGATOR_TYPE:
                return __('messages.other_investigator');
                break;
            case Models\User::ORG_CRC_TYPE:
                return __('messages.crc');
                break;
            case Models\User::ORG_NURSE_TYPE:
                return __('messages.nurse');
                break;
            case Models\User::ORG_CLINICAL_ENGINEER_TYPE:
                return __('messages.clinical_engineer');
                break;
            default:
                return '';
        }
    }
}