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

class CalcService
{
    public function getDefaultFlow()
    {
        return Converter\VentilatorValuesConverter::convertToDefaultFlowResult();
    }

    public function getEstimatedData()
    {
        return Converter\VentilatorValuesConverter::convertToEstimatedDataResult();
    }

    public function getIeManual()
    {
        return Converter\IeConverter::convertToIeResult();
    }

    public function getIeSound()
    {
        return Converter\IeConverter::convertToIeResult();
    }
}
