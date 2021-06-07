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

    Route::get('/qr', 'ManualController@showQrManual')->name('manual.qr');
    
    Route::get('/auth', 'ManualController@showAuthManual')->name('manual.auth');

    Route::get('/patient_setting', 'ManualController@showPatientSetting')->name('manual.patient_setting');

    Route::get('/ventilator_setting', 'ManualController@showVentilatorSetting')->name('manual.ventilator_setting');

    Route::get('/manual_measurement', 'ManualController@showManualMeasurement')->name('manual.manual_measurement');

    Route::get('/sound_measurement', 'ManualController@showSoundMeasurement')->name('manual.sound_measurement');

    Route::get('/ventilator_result', 'ManualController@showVentilatorResult')->name('manual.ventilator_result');
});