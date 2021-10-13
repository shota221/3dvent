<?php 

namespace App\Services\Support\Converter\Type;

use App\Models;

class Authority
{
    public static function convertToAuthority(int $authority_type) {
        switch ($authority_type) {
            case Models\User::ADMIN_PROJETCT_MANAGER_TYPE:
                return Models\User::ADMIN_PROJETCT_MANAGER_AUTHOIRTY;
                break;
            case Models\User::ADMIN_DATA_MANAGER_TYPE:
                return Models\User::ADMIN_DATA_MANAGER_AUTHOIRTY;
                break;
            case Models\User::ADMIN_DATA_MONITOR_TYPE:
                return Models\User::ADMIN_DATA_MONITOR_AUTHOIRTY;
                break;
            case Models\User::ADMIN_PRINCIPAL_INVESTIGATOR_TYPE:
                return Models\User::ADMIN_PRINCIPAL_INVESTIGATOR_AUTHOIRTY;
                break;
            case Models\User::ADMIN_COMPANY_TYPE:
                return Models\User::ADMIN_COMPANY_AUTHOIRTY;
                break;
            case Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE:
                return Models\User::ORG_PRINCIPAL_INVESTIGATOR_AUTHOIRTY;
                break;
            case Models\User::ORG_OTHRE_INVESTIGATOR_TYPE:
                return Models\User::ORG_OTHRE_INVESTIGATOR_AUTHOIRTY;
                break;
            case Models\User::ORG_CRC_TYPE:
                return Models\User::ORG_CRC_AUTHOIRTY;
                break;
            case Models\User::ORG_NURSE_TYPE:
                return Models\User::ORG_NURSE_AUTHOIRTY;
                break;
            case Models\User::ORG_CLINICAL_ENGINEER_TYPE:
                return Models\User::ORG_CLINICAL_ENGINEER_AUTHOIRTY;
                break;
        }
    }
}