<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientValueHistory extends BaseModel
{
    const
    UPDATED_AT = null,
    CREATE = 1,
    DELETE = 2;
}
