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

Route::group(['middleware' => ['routetype:org']], function () {

    // ログイン、ログアウト等認証不要ルート

    // ログイン画面
    Route::get(
        'auth',
        'AuthController@index'
    )->name('org.auth');

    // ログイン
    Route::post(
        'auth/login',
        'AuthController@login'
    )->name('org.login');

    // ログアウト
    Route::get(
        'auth/logout',
        'AuthController@logout'
    )->name('org.logout');

    // TODO　パスワードリセット

    Route::group(['middleware' => ['auth:org']], function () {

        // 認証が必要なルート

        // ダッシュボード
        Route::get(
            '/',
            'DashboardController@index'
        )->name('org.home');

        /**
         * 組織設定管理
         */
        Route::get(
            '/setting',
            'OrganizationSettingController@index'
        )->name('org.setting.index');

        Route::put(
            '/setting',
            'OrganizationSettingController@asyncUpdate'
        )->name('org.setting.update');

        /**
         * 患者観察研究データ管理
         */
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
            'PatientValueController@asyncGetDetail'
        )->name('org.patient_value.detail');

        Route::get(
            '/patient_value/search',
            'PatientValueController@asyncSearch'
        )->name('org.patient_value.search');

        /**
         * ユーザー管理
         */
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
            'UserController@asyncGetDetail'
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
            'UserController@exportUserCsvFormat'
        )->name('org.user.export_user_csv_format');

        Route::post(
            '/user/csv',
            'UserController@asyncImportUserCsvData'
        )->name('org.user.import_user_csv_data');

        // 組織設定管理
        Route::get(
            '/setting',
            'OrganizationSettingController@index'
        )->name('org.setting.index');

        Route::put(
            '/setting',
            'OrganizationSettingController@asyncUpdate'
        )->name('org.setting.update');

        /**
         * MicroVent管理
         */
        Route::get(
            '/ventilator',
            'VentilatorController@index'
        )->name('org.ventilator.index');

        Route::get(
            '/ventilator/async',
            'VentilatorController@asyncSearch'
        )->name('org.ventilator.async');

        Route::get(
            '/ventilator/async/patient',
            'VentilatorController@asyncPatient'
        )->name('org.ventilator.patient');

        Route::get(
            '/ventilator/async/bugs',
            'VentilatorController@asyncBugs'
        )->name('org.ventilator.bugs');

        Route::put(
            '/ventilator',
            'VentilatorController@asyncUpdate'
        )->name('org.ventilator.update');

        Route::delete(
            '/ventilator',
            'VentilatorController@asyncBulkDelete'
        )->name('org.ventilator.bulk_delete');
    });
});
