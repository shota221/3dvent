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
    Route::get(
        '/setting',
        'OrganizationSettingController@index'
    )->name('org.setting.index');
    
    Route::put(
        '/setting',
        'OrganizationSettingController@asyncUpdate'
    )->name('org.setting.update');

    // 患者観察研究データ管理
    Route::get(
        '/patient_value', 
        'PatientValueController@index'
    )->name('org.patient_value.index');
    
    Route::put(
        '/patient_value', 
        'PatientValueController@asyncUpdate'
    )->name('org.patient_value.update');
    
    Route::delete(
        '/patient_value', 
        'PatientValueController@asyncLogicalDelete'
    )->name('org.patient_value.logical_delete');
    
    Route::get(
        '/patient_value/detail', 
        'PatientValueController@asyncDetail'
    )->name('org.patient_value.detail');
    
    Route::get(
        '/patient_value/search', 
        'PatientValueController@asyncSearch'
    )->name('org.patient_value.search');

    //　ユーザー管理
    Route::get(
        '/user', 
        'UserController@index'
    )->name('org.user.index');
    
    Route::get(
        '/user/search', 
        'UserController@asyncSearch'
    )->name('org.user.search');

    Route::get(
        '/user/detail', 
        'UserController@asyncDetail'
    )->name('org.user.detail');
    
    Route::put(
        '/user', 
        'UserController@asyncUpdate'
        )->name('org.user.update');
        
    Route::post(
        '/user', 
        'UserController@asyncCreate'
    )->name('org.user.create');
    
    Route::delete(
        '/user', 
        'UserController@asyncLogicalDelete'
    )->name('org.user.logical_delete');

    Route::get(
        '/user/csv', 
        'UserController@asyncExportCsvUserFormat'
    )->name('org.user.export_csv_user_format');
    
    Route::post(
        '/user/csv', 
        'UserController@asyncImportCsvUserData'
    )->name('org.user.import_csv_user_data');
});