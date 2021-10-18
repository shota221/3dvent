<?php 

namespace App\Services\Support\Converter\Type;

use App\Http\Auth;

class Authority
{
    //convertToAuthority
    public static function convertToAdminAuthority(int $admin_authority_type) {
        switch ($admin_authority_type) {
            case Auth\AdminUserGate::AUTHORITIES['project_manager']['type']:           
                return Auth\AdminUserGate::AUTHORITIES['project_manager']['authority'];
                break;
            case Auth\AdminUserGate::AUTHORITIES['data_manager']['type']:
                return Auth\AdminUserGate::AUTHORITIES['data_manager']['authority'];
                break;
            case Auth\AdminUserGate::AUTHORITIES['data_monitor']['type']:
                return Auth\AdminUserGate::AUTHORITIES['data_monitor']['authority'];
                break;
            case Auth\AdminUserGate::AUTHORITIES['overall_principal_investigator']['type']:
                return Auth\AdminUserGate::AUTHORITIES['overall_principal_investigator']['authority'];
                break;
            case Auth\AdminUserGate::AUTHORITIES['company']['type']:
                return Auth\AdminUserGate::AUTHORITIES['company']['authority'];
                break;
        }
    }
    
    public static function convertToOrgAuthority(int $org_authority_type) {
        switch ($org_authority_type) {
            case Auth\OrgUserGate::AUTHORITIES['principal_investigator']['type']:
                return Auth\OrgUserGate::AUTHORITIES['principal_investigator']['authority'];
                break;
            case Auth\OrgUserGate::AUTHORITIES['other_investigator']['type']:
                return Auth\OrgUserGate::AUTHORITIES['other_investigator']['authority'];
                break;
            case Auth\OrgUserGate::AUTHORITIES['crc']['type']:
                return Auth\OrgUserGate::AUTHORITIES['crc']['authority'];
                break;
            case Auth\OrgUserGate::AUTHORITIES['nurse']['type']:
                return Auth\OrgUserGate::AUTHORITIES['nurse']['authority'];
                break;
            case Auth\OrgUserGate::AUTHORITIES['clinical_engineer']['type']:
                return Auth\OrgUserGate::AUTHORITIES['clinical_engineer']['authority'];
                break;
        }
    }
}