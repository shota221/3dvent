<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Appkey extends Authenticatable
{
    use HasFactory, Notifiable;

    const
        UPDATED_AT = null,
        KEY_COLUMN_NAME = 'appkey'
        ;
}
