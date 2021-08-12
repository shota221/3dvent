<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ventilator extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function setLocationAttribute(array $latlng)
    {
        $this->attributes['location'] = \DB::raw("(ST_GeomFromText('POINT(" . $latlng['lng'] . " " . $latlng['lat'] . ")'))");
    }

    public function getLatAttribute() 
    {
        return $this->attributes['lat'];
    }

    public function getLngAttribute()
    {
        return $this->attributes['lng'];
    }
}
