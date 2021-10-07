<?php

namespace App\Http\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserGate
{
    const
        /**
         * 第1bitでadminかorgかを判別する。
         */
        //000000000000000001
        ROLE_MASK = 1,

        //各ロール値
        ROLE_ADMIN = 1,
        ROLE_ORG = 0,

        /**
         * 呼吸器閲覧・編集権限(各2bit)
         * 2bitずつで各権限の及びそのスコープを表現。
         * うち第1bitは権限の有:1,無:0、第2bitはスコープの広さ(1:全範囲,0:制限有り※後述)を表す
         * adminの場合、11はすべてのデータ、01はすべての研究参加施設内データに対して権限有り
         * orgの場合、11はすべての自施設内データ、01は担当患者データに対して権限有り
         */
        //000000000000000010
        VENTILATOR_READABLE = 2,
        //000000000000001000
        VENTILATOR_EDITABLE = 8,

        /**
         * 機器観察研究データ閲覧・編集権限(各2bit)
         */
        //000000000000100000
        VENTILATOR_VALUE_READABLE = 32,
        //000000000010000000
        VENTILATOR_VALUE_EDITABLE = 128,

        /**
         * 患者観察研究データ閲覧・編集権限(各2bit)
         */
        //000000001000000000
        PATIENT_VALUE_READABLE = 512,
        //000000100000000000
        PATIENT_VALUE_EDITABLE = 2048,

        /**
         * 組織データ(各1bit)admin向け権限
         */
        //000010000000000000
        ORGANIZATION_READABLE = 8192,
        //000100000000000000
        ORGANIZATION_EDITABLE = 16384,

        /**
         * ユーザーデータ(各1bit)org向け権限
         */
        //001000000000000000
        USER_READABLE = 32768,
        //010000000000000000
        USER_EDITABLE = 65536,

        /**
         * 機器設定値(1bit)org向け権限
         */
        //100000000000000000
        ORGANIZATION_SETTING_EDITABLE = 131072,

    
        /**
         * 権限スコープの広さ
         * TODO:各操作に対するスコープの適用(scopeOfVentilatorValueEditableがORG_RESTRICTEDのユーザによるventilator_value操作については担当患者のみ編集可能とする等)
         */
        //全データ
        ADMIN_ALL = 1,
        //研究参加施設内データ
        ADMIN_RESTRICTED = 0,
        //自施設内全データ
        ORG_ALL = 1,
        //担当患者データ
        ORG_RESTRICTED = 0
    ;



    /**
     * admin（◯：全データ、△：研究参加施設のデータ、✕：権限なし）
     */
    //　　　　　　　　　　　呼吸器データ(閲覧)　呼吸器データ(編集) 機器観察研究データ(閲覧)　機器観察研究データ(編集) 患者観察研究データ(閲覧) 患者観察研究データ(編集)　組織データ(閲覧)　組織データ（編集） ユーザーデータ(閲覧) ユーザーデータ(編集)　機器設定値(編集)   権限                値
    //プロジェクト運営者　　◯                   ◯               ◯                       ◯                   ◯                       ◯                      ◯               ◯               ✕                  ✕                  ✕               000111111111111111  32767
    //データマネージャー　　◯                   ◯               ◯                       ◯                   ◯                       ◯                      ◯               ◯               ✕                  ✕                  ✕               000111111111111111  32767
    //データモニター　　　　◯                   ✕               ◯                       ✕                   ◯                       ✕                      ◯               ✕               ✕                  ✕                  ✕               000010011001100111  9831
    //医師（全体研究代表者）◯                   ✕               ◯                       ✕                   △                       ✕                      ◯               ✕               ✕                  ✕                  ✕               000010001001100111  8807
    //企業管理ユーザ　　　　◯                   ✕               ✕                       ✕                   ✕                       ✕                      ◯               ✕               ✕                  ✕                  ✕               000010000000000111  8199
    
    //test_admin_user = 111111111111111111 -> 262143

    /**
     * org（◯：自施設内全データ、△：担当患者のデータ、✕：権限なし）
     */
    //　　　　　　　　　　　呼吸器データ(閲覧)　呼吸器データ(編集) 機器観察研究データ(閲覧)　機器観察研究データ(編集) 患者観察研究データ(閲覧) 患者観察研究データ(編集)　組織データ(閲覧)　組織データ（編集） ユーザーデータ(閲覧) ユーザーデータ(編集)　機器設定値(編集)   権限                    
    //医師（施設内研究代表者）◯                   ◯               ◯                       ◯                   ◯                       ◯                      ✕               ✕               ◯                  ◯                  ◯              111001111111111110  237566
    //医師（その他）　　　　　△                   △               △                       △                   △                       △                      ✕               ✕               ✕                  ✕                  ✕              000000101010101010  2730
    //CRC                   ◯                   ◯               ◯                       ◯                   ◯                       ◯                      ✕               ✕               ◯                  ◯                  ✕              011001111111111110  106494
    //看護師                △                   △               △                       △                   △                       △                      ✕               ✕               ✕                  ✕                  ✕               000000101010101010  2730
    //臨床工学技師           △                   △               △                       △                   △                       △                      ✕               ✕               ✕                  ✕                  ✕              000000101010101010  2730

    //test_org_user = 111111111111111110 -> 262142

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

        // 組織データ編集
        Gate::define('organization_editable', function ($user) {
            return 0 < ($user->authority & self::ORGANIZATION_EDITABLE);
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
     * 指定されたユーザーについて権限のスコープの広さを返す(上述：権限スコープの広さ参照)
     * (例)医師(施設内研究代表者)111001111111111110　に対して、scopeOfVentilatorReadableを作用すると1（自施設内全範囲）
     * 臨床工学技士00000111011111110　に対して、scopeOfVentilatorValueEditableを作用すると0（担当患者）
     */
    public static function scopeOfVentialtorReadable(User $user)
    {
        $scope_mask = self::VENTILATOR_READABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    public static function scopeOfVentilatorEditable(User $user)
    {
        $scope_mask = self::VENTILATOR_EDITABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    public static function scopeOfVentilatorValueReadable(User $user)
    {
        $scope_mask = self::VENTILATOR_VALUE_READABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    public static function scopeOfVentilatorValueEditable(User $user)
    {
        $scope_mask = self::VENTILATOR_VALUE_EDITABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    public static function scopeOfPatientValueReadable(User $user)
    {
        $scope_mask = self::PATIENT_VALUE_READABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    public static function scopeOfPatientValueEditable(User $user)
    {
        $scope_mask = self::PATIENT_VALUE_EDITABLE << 1;
        return ($user->authority & $scope_mask)/$scope_mask;
    }

    /**
     * adminユーザであれば1,orgユーザであれば0を返す。
     * @param User $user
     * @return Int
     */
    public static function roleOf(User $user)
    {
        return ($user->authority & (self::ROLE_MASK));
    }
}
