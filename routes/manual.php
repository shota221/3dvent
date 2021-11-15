<?php

/*
|--------------------------------------------------------------------------
| manual Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| 
|
*/
/** 
 * 記述ruleは以下で統一 
 *   Route::get('auth', 'AuthController@index')->name('form.auth');
 */

Route::group(['middleware' => ['routetype:manual']], function() {
    
    /**
     * 電子マニュアル
     */

    // 二次元コード読取ページマニュアル
    Route::get(
        'text/qr/{language_code}', 
        'ManualController@showQrTextManual'
    )->name('manual.qr');
    
    // 認証ページマニュアル
    Route::get(
        'text/auth/{language_code}', 
        'ManualController@showAuthTextManual'
    )->name('manual.auth');

    // 患者基本情報設定ページマニュアル
    Route::get(
        'text/patient_setting/{language_code}', 
        'ManualController@showPatientSettingTextManual'
    )->name('manual.patient_setting');

    // 機器設定ページマニュアル
    Route::get(
        'text/ventilator_setting/{language_code}', 
        'ManualController@showVentilatorSettingTextManual'
    )->name('manual.ventilator_setting');

    // 呼気吸気時間手動測定ページマニュアル
    Route::get(
        'text/manual_measurement/{language_code}', 
        'ManualController@showManualMeasurementTextManual'
    )->name('manual.manual_measurement');

    // 呼気吸気時間音声測定ページマニュアル
    Route::get(
        'text/sound_measurement/{language_code}', 
        'ManualController@showSoundMeasurementTextManual'
    )->name('manual.sound_measurement');

    // 登録結果ページマニュアル
    Route::get(
        'text/ventilator_result/{language_code}', 
        'ManualController@showVentilatorResultTextManual'
    )->name('manual.ventilator_result');
 
    // 電子ページマニュアル（全マニュアル表示）
    Route::get(
        'text/{language_code}', 
        'ManualController@showTextManualAll'
    )->name('manual.text_manual_all');


    /**
     * 動画マニュアル
     */

    // 動画ページマニュアル（全マニュアル表示）
    Route::get(
        'video/{language_code}', 
        'ManualController@showVideoManual'
    )->name('manual.video_manual_all');

});