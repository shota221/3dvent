<?php

namespace App\Services\Support\Client\Response;

class ReverseGeocodingResponse
{
    public $place_id;

    public $licence;

    public $osm_type;

    public $osm_id;

    public $lat;

    public $lon;

    public $display_name;

    public $address;

    public $boundingbox;

    public function __construct($data)
    {
        $this->place_id = $data['place_id'];

        $this->licence = $data['licence'];

        $this->osm_type = $data['osm_type'];

        $this->osm_id = $data['osm_id'];

        $this->lat = $data['lat'];

        $this->lon = $data['lon'];

        $this->display_name = $data['display_name'];

        $this->address = $data['address'];

        $this->boundingbox = $data['boundingbox'];
    }
}
