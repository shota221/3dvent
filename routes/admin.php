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

    // ログイン、ログアウト等認証不要ルート

    // ログイン画面
    Route::get(
        'auth',
        'AuthController@index'
    )->name('admin.auth');

    // ログイン
    Route::post(
        'auth/login',
        'AuthController@login'
    )->name('admin.login');
    
    // ログアウト
    Route::get(
        'auth/logout',
        'AuthController@logout'
    )->name('admin.logout');

    // TODO　パスワードリセット

    Route::group(['middleware' => ['auth:admin']], function () {

        // 認証が必要なルート
        
        // ダッシュボード
        Route::get(
            '/',
            'DashboardController@index'
        )->name('admin.home');
    
        /**
         * 組織管理
         */
        Route::get('/organization', 'OrganizationController@index')->name('admin.organization.index');
        Route::get('/organization/async', 'OrganizationController@asyncSearch')->name('admin.organization.async');
        Route::get('/organization/async/search_list', 'OrganizationController@asyncSearchList')->name('admin.organization.search_list');
        Route::get('/organization/async/users', 'OrganizationController@asyncUsers')->name('admin.organization.users');
        Route::post('/organization', 'OrganizationController@asyncCreate')->name('admin.organization.create');
        Route::put('/organization', 'OrganizationController@asyncUpdate')->name('admin.organization.update');
    
        /**
         * 組織管理者ユーザー管理
         */
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
    
        Route::get(
            '/patient_value/search', 
            'PatientValueController@asyncSearch'
        )->name('admin.patient_value.search');
        
        Route::get(
            '/patient_value/async/organization_data', 
            'PatientValueController@asyncDataOrganization'
        )->name('admin.patient_value.async.organization_data');

        /**
         * MicroVent管理
         */
        Route::get('/ventilator', 'VentilatorController@index')->name('admin.ventilator.index');
        Route::get('/ventilator/async', 'VentilatorController@asyncSearch')->name('admin.ventilator.async');
        Route::get('/ventilator/async/patient','VentilatorController@asyncPatient')->name('admin.ventilator.patient');
        Route::get('/ventilator/async/bugs','VentilatorController@asyncShowBugList')->name('admin.ventilator.bugs');
        Route::get('/ventilator/csv','VentilatorController@exportCsv')->name('admin.ventilator.export_csv');
        Route::post('/ventilator/csv','VentilatorController@importCsv')->name('admin.ventilator.import_csv');
        Route::put('/ventilator', 'VentilatorController@asyncUpdate')->name('admin.ventilator.update');
        Route::delete('/ventilator','VentilatorController@asyncBulkDelete')->name('admin.ventilator.bulk_delete');

        /**
         * 機器観察研究データ
         */
        Route::get('/ventilator_value', 'VentilatorValueController@index')->name('admin.ventilator_value.index');
        Route::post('/ventilator_value', 'VentilatorValueController@index')->name('admin.ventilator_value.by_ventilator');
        Route::get('/ventilator_value/detail', 'VentilatorValueController@asyncDetail')->name('admin.ventilator_value.detail');
        Route::get('/ventilator_value/search', 'VentilatorValueController@asyncSearch')->name('admin.ventilator_value.search');
        Route::put('/ventilator_value', 'VentilatorValueController@asyncUpdate')->name('admin.ventilator_value.update');
        Route::delete('/ventilator_value','VentilatorValueController@asyncBulkDelete')->name('admin.ventilator_value.bulk_delete');

        /**
         * 患者観察研究データ管理
         */
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
});
