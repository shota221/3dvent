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
 *   Route::get('auth', 'AuthController@index')->name('admin.auth');
 */

Route::group(['middleware' => ['routetype:admin']], function () {
    Route::get('/organization', 'OrganizationController@index')->name('admin.organization.index');
    Route::get('/organization/async', 'OrganizationController@asyncSearch')->name('admin.organization.async');
    Route::post('/organization', 'OrganizationController@asyncCreate')->name('admin.organization.create');
    Route::put('/organization', 'OrganizationController@asyncUpdate')->name('admin.organization.update');

    //　組織管理者ユーザー管理
    Route::get(
        '/org_admin_user', 
        'OrganizationAdminUserController@index'
    )->name('admin.org_admin_user.index');
    
    Route::post(
        '/org_admin_user', 
        'OrganizationAdminUserController@asyncCreate'
    )->name('admin.org_admin_user.create');
    
    Route::put(
        '/org_admin_user', 
        'OrganizationAdminUserController@asyncUpdate'
    )->name('admin.org_admin_user.update');
    
    Route::get(
        '/org_admin_user/search', 
        'OrganizationAdminUserController@asyncSearch'
    )->name('admin.org_admin_user.search');
    
    Route::get(
        '/org_admin_user/detail', 
        'OrganizationAdminUserController@asyncDetail'
    )->name('admin.org_admin_user.detail');
    
    Route::get(
        '/org_admin_user/async/organization_data', 
        'OrganizationAdminUserController@asyncDataOrganization'
    )->name('admin.org_admin_user.async.organization_data');

    // 患者観察研究データ管理
    Route::get(
        '/patient_value', 
        'PatientValueController@index'
    )->name('admin.patient_value.index');
    
    Route::put(
        '/patient_value', 
        'PatientValueController@asyncUpdate'
    )->name('admin.patient_value.update');
    
    Route::delete(
        '/patient_value', 
        'PatientValueController@asyncLogicalDelete'
    )->name('admin.patient_value.logical_delete');
    
    Route::get(
        '/patient_value/detail', 
        'PatientValueController@asyncDetail'
    )->name('admin.patient_value.detail');
    
    Route::get(
        '/patient_value/search', 
        'PatientValueController@asyncSearch'
    )->name('admin.patient_value.search');
    
    Route::get(
        '/patient_value/async/organization_data', 
        'PatientValueController@asyncDataOrganization'
    )->name('admin.patient_value.async.organization_data');

});
