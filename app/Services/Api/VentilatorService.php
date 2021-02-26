<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Support\Converter;

class VentilatorService
{
    public function getVentilatorResult()
    {
        return Converter\VentilatorConverter::convertToVentilatorResult();
    }

    public function create()
    {
        return Converter\VentilatorConverter::convertToVentilatorRegistrationResult();
    }

    public function getVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueResult();
    }

    public function createVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueRegistrationResult();
    }

    public function updateVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueUpdateResult();
    }
}