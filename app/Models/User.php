<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    const 
        TOKEN_COLUMN_NAME = 'api_token',
        DISABLED = 1,
        ENABLED = 0,
        ROLE_ADMIN = 1
    ;

    const 
        // admin用権限
        ADMIN_PROJETCT_MANAGER_AUTHORITY       = 16383, // プロジェクト運営者
        ADMIN_DATA_MANAGER_AUTHORITY           = 16383, // データマネージャー
        ADMIN_DATA_MONITOR_AUTHORITY           = 4915,  // データモニター
        ADMIN_PRINCIPAL_INVESTIGATOR_AUTHORITY = 4403,  // 医師（全体研究代表者）
        ADMIN_COMPANY_AUTHORITY                = 4099,  // 企業管理ユーザー
        // org用権限
        ORG_PRINCIPAL_INVESTIGATOR_AUTHORITY   = 32767,  // 医師（施設内研究代表者）
        ORG_OTHRE_INVESTIGATOR_AUTHORITY       = 1365,   // 医師（その他）
        ORG_CRC_AUTHORITY                      = 16383,  // CRC
        ORG_NURSE_AUTHORITY                    = 1365,   // 看護師
        ORG_CLINICAL_ENGINEER_AUTHORITY        = 1365    // 臨床工学士
    ;
   
    const 
        // admin用権限タイプ
        ADMIN_PROJETCT_MANAGER_TYPE       = 1, // プロジェクト運営者
        ADMIN_DATA_MANAGER_TYPE           = 2, // データマネージャー
        ADMIN_DATA_MONITOR_TYPE           = 3, // データモニター
        ADMIN_PRINCIPAL_INVESTIGATOR_TYPE = 4, // 医師（全体研究代表者）
        ADMIN_COMPANY_TYPE                = 5, // 企業管理ユーザー
        // org用権限タイプ
        ORG_PRINCIPAL_INVESTIGATOR_TYPE   = 1, // 医師（施設内研究代表者）
        ORG_OTHRE_INVESTIGATOR_TYPE       = 2, // 医師（その他）
        ORG_CRC_TYPE                      = 3, // CRC
        ORG_NURSE_TYPE                    = 4, // 看護師
        ORG_CLINICAL_ENGINEER_TYPE        = 5 // 臨床工学士
    ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function active()
    {
        return ! boolval($this->disaled_flg);
    }

    public static function tableName()
    {
        return Str::snake(Str::pluralStudly(class_basename(static::class)));
    }

    public function hasRoleAdmin()
    {
        return $this->admin_flg === self::ROLE_ADMIN;
    }

    public function hasRoleOrg()
    {
        return $this->admin_flg !== self::ROLE_ADMIN;
    }
}
