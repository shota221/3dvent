<?php 

namespace App\Services\Support\Converter\Lang;

use App\Http\Auth;

class Authority 
{
    // convertToAuthorityName
    public static function convertToAdminAuthorityName(int $admin_authority_type)
    {
        switch ($admin_authority_type) {
            case Auth\AdminUserGate::AUTHORITIES['project_manager']['type']:
                return __('messages.project_manager');
                break;
            case Auth\AdminUserGate::AUTHORITIES['data_manager']['type']:
                return __('messages.data_manager');
                break;
            case Auth\AdminUserGate::AUTHORITIES['data_monitor']['type']:
                return __('messages.data_monitor');
                break;
            case Auth\AdminUserGate::AUTHORITIES['overall_principal_investigator']['type']:
                return __('messages.overall_principal_investigator');
                break;
            case Auth\AdminUserGate::AUTHORITIES['company']['type']:
                return __('messages.company');
                break;
            default:
                return '';
        }
    }

    public static function convertToOrgAuthorityName(int $org_authority_type)
    {
        switch ($org_authority_type) {
            case Auth\OrgUserGate::AUTHORITIES['principal_investigator']['type']:
                return __('messages.principal_investigator');
                break;
            case Auth\OrgUserGate::AUTHORITIES['other_investigator']['type']:
                return __('messages.other_investigator');
                break;
            case Auth\OrgUserGate::AUTHORITIES['crc']['type']:
                return __('messages.crc');
                break;
            case Auth\OrgUserGate::AUTHORITIES['nurse']['type']:
                return __('messages.nurse');
                break;
            case Auth\OrgUserGate::AUTHORITIES['clinical_engineer']['type']:
                return __('messages.clinical_engineer');
                break;
            default:
                return '';
        }
    }
}