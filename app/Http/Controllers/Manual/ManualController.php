<?php

namespace App\Http\Controllers\Manual;

use App\Http\Controllers\Controller;
use App\Http\Forms\Manual as Form;
use Illuminate\Http\Request;

/**
 * アプリに定義されていない言語コードがリクエストとして来た場合には、
 * アプリのデフォルト言語をフォームオブジェクトにセット
 */
class ManualController extends Controller
{
    public function showQrTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));

        return view('Text/' . $form->language_code . '/qr/manual');
    }

    public function showAuthTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));

        return view('Text/' . $form->language_code . '/auth/manual');
    }

    public function showPatientSettingTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));

        return view('Text/' . $form->language_code . '/patientSetting/manual');
    }

    public function showVentilatorSettingTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));

        return view('Text/' . $form->language_code . '/ventilatorSetting/manual');
    }

    public function showManualMeasurementTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));
        
        return view('Text/' . $form->language_code . '/manualMeasurement/manual');
    }

    public function showSoundMeasurementTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));
        
        return view('Text/' . $form->language_code . '/soundMeasurement/manual');
    }

    public function showVentilatorResultTextManual(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));
        
        return view('Text/' . $form->language_code . '/ventilatorResult/manual');
    }

    public function showTextManualAll(string $language_code) 
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));
        
        return view('Text/' . $form->language_code . '/manual');
    }

    public function showVideoManual(string $language_code)
    {
        $form = new Form\LanguageCodeForm(compact('language_code'));
        
        return view('Video/' .$form->language_code . '/manual');
    }

}
