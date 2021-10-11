<?php

namespace App\Http\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class OrgUserGate
{
    const
        /**
         * 呼吸器閲覧・編集権限
         */
        //000000000000001
        VENTILATOR_READABLE = 1,
        //000000000000010
        //VENTILATOR_READABLEが立っていてVENTILATOR_READABLE_ALLが立っていない場合、担当患者に対してのみ閲覧権限を有する。（以下同様）
        VENTILATOR_READABLE_ALL = 2,
        //000000000000100
        VENTILATOR_EDITABLE = 4,
        //000000000001000
        VENTILATOR_EDITABLE_ALL = 8,

        /**
         * 機器観察研究データ閲覧・編集権限
         */
        //000000000010000
        VENTILATOR_VALUE_READABLE = 16,
        //000000000100000
        VENTILATOR_VALUE_READABLE_ALL = 32,
        //000000001000000
        VENTILATOR_VALUE_EDITABLE = 64,
        //000000010000000
        VENTILATOR_VALUE_EDITABLE_ALL = 128,

        /**
         * 患者観察研究データ閲覧・編集権限
         */
        //000000100000000
        PATIENT_VALUE_READABLE = 256,
        //000001000000000
        PATIENT_VALUE_READABLE_ALL = 512,
        //000010000000000
        PATIENT_VALUE_EDITABLE = 1024,
        //000100000000000
        PATIENT_VALUE_EDITABLE_ALL = 2048,

        /**
         * ユーザーデータ
         */
        //001000000000000
        USER_READABLE = 4096,
        //010000000000000
        USER_EDITABLE = 8192,

        /**
         * 機器設定値
         */
        //1000000000000000
        ORGANIZATION_SETTING_EDITABLE = 16384
    ;

    /**
     * org（◯：自施設内全データ、△：担当患者のデータ、✕：権限なし）
     */
    //　　　　　　　　　　　呼吸器データ(閲覧)　呼吸器データ(編集) 機器観察研究データ(閲覧)　機器観察研究データ(編集) 患者観察研究データ(閲覧) 患者観察研究データ(編集) ユーザーデータ(閲覧) ユーザーデータ(編集)　機器設定値(編集)   権限                    
    //医師（施設内研究代表者）◯                   ◯               ◯                       ◯                   ◯                       ◯                   ◯                  ◯                  ◯              111111111111111  32767
    //医師（その他）　　　　　△                   △               △                       △                   △                       △                   ✕                  ✕                  ✕              000010101010101  1365
    //CRC                   ◯                   ◯               ◯                       ◯                   ◯                       ◯                   ◯                  ◯                  ✕              011111111111111  16383
    //看護師                △                   △               △                       △                   △                       △                   ✕                  ✕                  ✕               000010101010101  1365
    //臨床工学技師           △                   △               △                       △                   △                       △                   ✕                  ✕                  ✕              000010101010101  1365

    //test_org_user = 111111111111111 -> 32767

    public static function define()
    {
        /**
         * 閲覧
         */
        // 呼吸器データ閲覧
        Gate::define('ventilator_readable', function ($user) {
            return 0 < ($user->authority & self::VENTILATOR_READABLE);
        });

        // 機器観察研究データ閲覧
        Gate::define('ventilator_value_readable', function ($user) {
            return 0 < ($user->authority & self::VENTILATOR_VALUE_READABLE);
        });

        // 患者観察研究データ閲覧
        Gate::define('patient_value_readable', function ($user) {
            return 0 < ($user->authority & self::PATIENT_VALUE_READABLE);
        });

        // ユーザデータ閲覧
        Gate::define('user_readable', function ($user) {
            return 0 < ($user->authority & self::USER_READABLE);
        });

        /**
         * 編集
         */
        // 呼吸器データ編集
        Gate::define('ventilator_editable', function ($user) {
            return 0 < ($user->authority & self::VENTILATOR_EDITABLE);
        });

        // 機器観察研究データ編集
        Gate::define('ventilator_value_editable', function ($user) {
            return 0 < ($user->authority & self::VENTILATOR_VALUE_EDITABLE);
        });

        // 患者観察研究データ編集
        Gate::define('patient_value_editable', function ($user) {
            return 0 < ($user->authority & self::PATIENT_VALUE_EDITABLE);
        });

        // ユーザデータ編集
        Gate::define('user_editable', function ($user) {
            return 0 < ($user->authority & self::USER_EDITABLE);
        });

        // 機器設定値編集
        Gate::define('organization_setting_editable', function ($user) {
            return 0 < ($user->authority & self::ORGANIZATION_SETTING_EDITABLE);
        });
    }

    /**
     * 自施設内のすべてのデータに対して権限を有するかどうか。
     * falseである場合は担当患者に対してのみ権限を有する。
     */
    public static function canReadAllVentilator(User $user)
    {
        return 0 < ($user->authority & self::VENTILATOR_READABLE_ALL);
    }

    public static function canEditAllVentilator(User $user)
    {
        return 0 < ($user->authority & self::VENTILATOR_EDITABLE_ALL);
    }

    public static function canReadAllVentilatorValue(User $user)
    {
        return 0 < ($user->authority & self::VENTILATOR_VALUE_READABLE_ALL);
    }

    public static function canEditAllVentilatorValue(User $user)
    {
        return 0 < ($user->authority & self::VENTILATOR_VALUE_EDITABLE_ALL);
    }

    public static function canReadAllPatientValue(User $user)
    {
        return 0 < ($user->authority & self::PATIENT_VALUE_READABLE_ALL);
    }

    public static function canEditAllPatientValue(User $user)
    {
        return 0 < ($user->authority & self::PATIENT_VALUE_EDITABLE_ALL);
    }
}
