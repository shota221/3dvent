<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends BaseModel
{
    const
        PATIENT_OBS_APPROVED = 1,
        PATIENT_OBS_UNAPPROVED = 0
        ;
    
    public function getEdcLinkedFlgAttribute()
    {
        return !is_null($this->edcid);
    }

}
