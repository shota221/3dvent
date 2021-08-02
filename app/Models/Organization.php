<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends BaseModel
{
    const
        PATIENT_OBS_APPROVED = 1,
        PARIENT_OBS_UNAPPROVED = 0
        ;
}
