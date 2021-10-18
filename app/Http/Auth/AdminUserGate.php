<?php

namespace App\Http\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AdminUserGate
{
    const 
        AUTHORITIES = [
            // プロジェクト運営者
            'project_manager' => [
                'authority' => 16383,
                'type'      => 1,
            ],
            // データマネージャー
            'data_manager' => [
                'authority' => 16383,
                'type'      => 2,
            ],
            // データモニター
            'data_monitor' => [
                'authority' => 4915,
                'type'      => 3,
            ],
            // 医師（全体研究代表者）
            'overall_principal_investigator' => [
                'authority' => 4403,
                'type'      => 4,
            ],
            // 企業管理ユーザー
            'company' => [
                'authority' => 4099,
                'type'      => 5,
            ],
        ];

    const
        /**
         * 呼吸器閲覧・編集権限
         */
        //00000000000000001
        VENTILATOR_READABLE = 1,
        //00000000000000010
        //VENTILATOR_READABLEが立っていてVENTILATOR_READABLE_ALLが立っていない場合、研究参加施設のデータに対してのみ閲覧権限を有する。（以下同様）
        VENTILATOR_READABLE_ALL = 2,
        //00000000000000100
        VENTILATOR_EDITABLE = 4,
        //00000000000001000
        VENTILATOR_EDITABLE_ALL = 8,

        /**
         * 機器観察研究データ閲覧・編集権限
         */
        //00000000000010000
        VENTILATOR_VALUE_READABLE = 16,
        //00000000000100000
        VENTILATOR_VALUE_READABLE_ALL = 32,
        //00000000001000000
        VENTILATOR_VALUE_EDITABLE = 64,
        //00000000010000000
        VENTILATOR_VALUE_EDITABLE_ALL = 128,

        /**
         * 患者観察研究データ閲覧・編集権限
         */
        //00000000100000000
        PATIENT_VALUE_READABLE = 256,
        //00000001000000000
        PATIENT_VALUE_READABLE_ALL = 512,
        //00000010000000000
        PATIENT_VALUE_EDITABLE = 1024,
        //00000100000000000
        PATIENT_VALUE_EDITABLE_ALL = 2048,

        /**
         * 組織データ閲覧・編集権限
         */
        //00001000000000000
        ORGANIZATION_READABLE = 4096,
        //00010000000000000
        ORGANIZATION_EDITABLE = 8192
        ;



    /**
     * admin（◯：全データ、△：研究参加施設のデータ、✕：権限なし）
     */
    //　　　　　　　　　　　呼吸器データ(閲覧)　呼吸器データ(編集) 機器観察研究データ(閲覧)　機器観察研究データ(編集) 患者観察研究データ(閲覧) 患者観察研究データ(編集)　組織データ(閲覧)　組織データ（編集） 　 権限            値
    //プロジェクト運営者　　◯                   ◯               ◯                       ◯                   ◯                       ◯                      ◯               ◯               11111111111111  16383
    //データマネージャー　　◯                   ◯               ◯                       ◯                   ◯                       ◯                      ◯               ◯               11111111111111  16383
    //データモニター　　　　◯                   ✕               ◯                       ✕                   ◯                       ✕                      ◯               ✕               01001100110011  4915
    //医師（全体研究代表者）◯                   ✕               ◯                       ✕                   △                       ✕                      ◯               ✕               01000100110011  4403
    //企業管理ユーザ　　　　◯                   ✕               ✕                       ✕                   ✕                       ✕                      ◯               ✕               01000000000011  4099

    //test_admin_user = 11111111111111 -> 16383

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

        // 組織データ閲覧
        Gate::define('organization_readable', function ($user) {
            return 0 < ($user->authority & self::ORGANIZATION_READABLE);
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

        // 組織データ編集
        Gate::define('organization_editable', function ($user) {
            return 0 < ($user->authority & self::ORGANIZATION_EDITABLE);
        });
    }

    /**
     * すべてのデータに対して権限を有するかどうか。
     * falseである場合は研究参加組織に対してのみ権限を有する。
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
