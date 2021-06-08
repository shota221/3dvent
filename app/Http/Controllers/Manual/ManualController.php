<?php

namespace App\Http\Controllers\Manual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function showQrManual() {
        return view('/Manual/qrManual');
    }

    public function showAuthManual() {
        return view('/Manual/authManual');
    }

    public function showPatientSetting() {
        return view('/Manual/patientSettingManual');
    }

    public function showVentilatorSetting() {
        return view('/Manual/ventilatorSettingManual');
    }

    public function showManualMeasurement() {
        return view('/Manual/manualMeasurementManual');
    }

    public function showSoundMeasurement() {
        return view('/Manual/soundMeasurementManual');
    }

    public function showVentilatorResult() {
        return view('/Manual/ventilatorResultManual');
    }

}
