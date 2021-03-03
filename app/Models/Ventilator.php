<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventilator extends BaseModel
{
    protected $geometries = ['location'];

    public function getAttributeValue($key)
    {
        if (in_array($key, $this->geometryAttributes())) {
            $value = $this->getAttributeFromArray($key);
            return $this->getGeometryAtAttribute($value);
        }
        return parent::getAttributeValue($key);
    }

    private function geometryAttributes()
    {
        if (!property_exists($this, 'geometries')) {
            return [];
        }
        return $this->geometries;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->geometryAttributes())) {
            return $this->setGeometryAttribute($key, $value);
        }
        return parent::setAttribute($key, $value);
    }

    public function getGeometryAtAttribute(string $value)
    {
        $attribute = ['latitude' => '', 'longitude' => ''];
        if (!preg_match('/\APOINT\(([0-9\.]+) ([0-9\.]+)\)\z/', $value, $matches)) {
            return $attribute;
        }
        $attribute['latitude'] = $matches[1];
        $attribute['longitude'] = $matches[2];

        return $attribute;
    }

    public function setGeometryAttribute(string $key, array $latlng)
    {
        $this->attributes[$key] = \DB::raw("(ST_GeomFromText('POINT({$latlng['latitude']} {$latlng['longitude']})'))");
        return $this;
    }

    public function newQuery($excludeDeleted = true)
    {
        $rawColumns = ['*'];
        foreach ($this->geometries as $column) {
            $rawColumns[] = \DB::raw("ST_ASTEXT({$column}) AS {$column}");
        }

        return parent::newQuery($excludeDeleted)
            ->select($rawColumns);
    }
}
