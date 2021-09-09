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
 *   Route::get('auth', 'AuthController@index')->name('org.auth');
 */

Route::group(['middleware' => ['routetype:org']], function() {

    // 組織設定管理
    Route::get('/setting','OrganizationSettingController@index')->name('org.setting.index');
    Route::put('/setting','OrganizationSettingController@asyncUpdate')->name('org.setting.update');

    // 患者観察研究データ管理
    Route::get('/patient_value', 'PatientValueController@index')->name('org.patient_value.index');
    Route::put('/patient_value', 'PatientValueController@asyncUpdate')->name('org.patient_value.update');
    Route::delete('/patient_value', 'PatientValueController@asyncLogicalDelete')->name('org.patient_value.logical_delete');
    Route::get('/patient_value/edit', 'PatientValueController@asyncEdit')->name('org.patient_value.edit');
    Route::get('/patient_value/search', 'PatientValueController@asyncSearch')->name('org.patient_value.search');
});