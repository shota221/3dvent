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

class PatientService
{
    public function create()
    {
        return Converter\PatientConverter::convertToPatientRegistrationResult();
    }

    public function getPatientResult()
    {
        return Converter\PatientConverter::convertToPatientResult(json_decode('{"age": "28","gender": "男","nationality": "日本"}'));
    }

    public function update()
    {
        return Converter\PatientConverter::convertToPatientResult(json_decode('{"age": "28","gender": "男","nationality": "日本"}'));
    }
}
