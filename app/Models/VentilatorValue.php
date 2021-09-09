<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentilatorValue extends TraceableBaseModel
{
    const
        UPDATED_AT = null,
        FIX = 1,
        CONFIRM = 1,

        //gender
        MALE = 1,
        FEMALE = 2,

        //status_use
        RESPIRATORY_FAILURE = 1,
        SURGERY = 2,
        INSPECTION_PROCEDURE = 3,
        STATUS_USE_OTHER = 4
        ;
}
