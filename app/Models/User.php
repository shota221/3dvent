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
        AUTHORITY_ADMIN = 1 // TODO　権限回り実装後修正
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
        return boolval($this->authority === self::AUTHORITY_ADMIN);
    }

    public function hasRoleOrg()
    {
        return boolval($this->authority !== self::AUTHORITY_ADMIN);
    }
}
