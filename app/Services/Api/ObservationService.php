<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class ObservationService
{
    
    //TODO 以下補完作業
    public function count() {
         return json_decode(Converter\ObservationConverter::convertToObservationCount(), true);
    }

    public function getPatientList() {
        return json_decode(Converter\ObservationConverter::convertToPatientList(), true);
   }

   public function getVentilatorList() {
        return json_decode(Converter\ObservationConverter::convertToVentilatorList(), true);
    }

    public function getVentilatorBugList() {
        return json_decode(Converter\ObservationConverter::convertToVentilatorBugList(), true);
    }

}
