<?php 

namespace App\Services\Support\Converter\Type;

use App\Models;

class Authority
{
    //convertToAuthority
    public static function convertToAdminAuthority(int $admin_authority_type) {
        switch ($admin_authority_type) {
            case Models\User::ADMIN_PROJETCT_MANAGER_TYPE:           
                return Models\User::ADMIN_PROJETCT_MANAGER_AUTHORITY;
                break;
            case Models\User::ADMIN_DATA_MANAGER_TYPE:
                return Models\User::ADMIN_DATA_MANAGER_AUTHORITY;
                break;
            case Models\User::ADMIN_DATA_MONITOR_TYPE:
                return Models\User::ADMIN_DATA_MONITOR_AUTHORITY;
                break;
            case Models\User::ADMIN_PRINCIPAL_INVESTIGATOR_TYPE:
                return Models\User::ADMIN_PRINCIPAL_INVESTIGATOR_AUTHORITY;
                break;
            case Models\User::ADMIN_COMPANY_TYPE:
                return Models\User::ADMIN_COMPANY_AUTHORITY;
                break;
        }
    }
    
    public static function convertToOrgAuthority(int $org_authority_type) {
        switch ($org_authority_type) {
            case Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE:
                return Models\User::ORG_PRINCIPAL_INVESTIGATOR_AUTHORITY;
                break;
            case Models\User::ORG_OTHRE_INVESTIGATOR_TYPE:
                return Models\User::ORG_OTHRE_INVESTIGATOR_AUTHORITY;
                break;
            case Models\User::ORG_CRC_TYPE:
                return Models\User::ORG_CRC_AUTHORITY;
                break;
            case Models\User::ORG_NURSE_TYPE:
                return Models\User::ORG_NURSE_AUTHORITY;
                break;
            case Models\User::ORG_CLINICAL_ENGINEER_TYPE:
                return Models\User::ORG_CLINICAL_ENGINEER_AUTHORITY;
                break;
        }
    }
}