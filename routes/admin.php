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

    // 言語切替
    Route::get(
        'language/{language_code}',
        'LanguageController@asyncSwitchLanguage'
    )->name('admin.swich_language');

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

    // パスワード変更申請
    Route::post(
        'auth/password_reset_application',
        'AuthController@asyncApplyPasswordReset'
    )->name('admin.auth.async.apply_password_reset');

    // パスワード変更画面
    Route::get(
        'auth/password_reset/{token}',
        'AuthController@indexPasswordReset'
    )->name('admin.auth.password_reset');

    // パスワード変更
    Route::put(
        'auth/password_reset',
        'AuthController@asyncResetPassword'
    )->name('admin.auth.async.reset_password');
    
    Route::group(['middleware' => ['auth:admin']], function () {

        // 認証が必要なルート

        // プロフィール取得
        Route::get(
            '/account/async/data_profile',
            'AccountController@asyncDataProfile'
        )->name('admin.account.async.data_profile');

        // プロフィール更新
        Route::put(
            '/account/async/profile',
            'AccountController@asyncUpdateProfile'
        )->name('admin.account.async.profile');

        // ダッシュボード
        Route::get(
            '/',
            'DashboardController@index'
        )->name('admin.home');

        /**
         * 組織管理
         */
        Route::group(['middleware' => ['can:organization_readable']], function () {

            Route::get(
                '/organization',
                'OrganizationController@index'
            )->name('admin.organization.index');

            Route::get(
                '/organizations',
                'OrganizationController@asyncSearch'
            )->name('admin.organization.async');

            //select2での組織選択用
            Route::get(
                '/organization/async/search_list',
                'OrganizationController@asyncSearchList'
            )->name('admin.organization.search_list');

            Route::get(
                '/organization/async/users',
                'OrganizationController@asyncUsers'
            )->name('admin.organization.users');
        });

        Route::group(['middleware' => ['can:organization_editable']], function () {

            Route::post(
                '/organization',
                'OrganizationController@asyncCreate'
            )->name('admin.organization.create');

            Route::put(
                '/organization',
                'OrganizationController@asyncUpdate'
            )->name('admin.organization.update');
        });

        /**
         * 組織管理者ユーザー管理
         */
        Route::group(['middleware' => ['can:organization_readable']], function () {
            Route::get(
                '/org_admin_user',
                'OrganizationAdminUserController@index'
            )->name('admin.org_admin_user.index');

            Route::get(
                '/org_admin_users',
                'OrganizationAdminUserController@asyncSearch'
            )->name('admin.org_admin_user.search');

            Route::get(
                '/org_admin_user/{id}',
                'OrganizationAdminUserController@asyncGetDetail'
            )->name('admin.org_admin_user.detail');

            Route::get(
                '/org_admin_user/async/organization_data',
                'OrganizationAdminUserController@asyncDataOrganization'
            )->name('admin.org_admin_user.async.organization_data');
        });

        Route::group(['middleware' => ['can:organization_editable']], function () {

            Route::post(
                '/org_admin_user',
                'OrganizationAdminUserController@asyncCreate'
            )->name('admin.org_admin_user.create');

            Route::put(
                '/org_admin_user',
                'OrganizationAdminUserController@asyncUpdate'
            )->name('admin.org_admin_user.update');
        });


        /**
         * MicroVent管理
         */
        Route::group(['middleware' => ['can:ventilator_readable']], function () {
            Route::get(
                '/ventilator',
                'VentilatorController@index'
            )->name('admin.ventilator.index');

            Route::get(
                '/ventilators',
                'VentilatorController@asyncSearch'
            )->name('admin.ventilator.async');

            Route::get(
                '/ventilator/async/patient',
                'VentilatorController@asyncGetPatientData'
            )->name('admin.ventilator.patient');

            Route::get(
                '/ventilator/async/bugs',
                'VentilatorController@asyncShowBugList'
            )->name('admin.ventilator.bugs');
        });

        Route::group(['middleware' => ['can:ventilator_editable']], function () {
            Route::get(
                '/ventilator/csv',
                'VentilatorController@exportCsv'
            )->name('admin.ventilator.export_csv');

            Route::post(
                '/ventilator/csv',
                'VentilatorController@importCsv'
            )->name('admin.ventilator.import_csv');

            Route::put(
                '/ventilator',
                'VentilatorController@asyncUpdate'
            )->name('admin.ventilator.update');

            Route::delete(
                '/ventilator',
                'VentilatorController@asyncBulkDelete'
            )->name('admin.ventilator.bulk_delete');
        });

        /**
         * 機器観察研究データ
         */
        Route::group(['middleware' => ['can:ventilator_value_readable']], function () {

            Route::get(
                '/ventilator_value',
                'VentilatorValueController@index'
            )->name('admin.ventilator_value.index');

            Route::post(
                '/ventilator_value',
                'VentilatorValueController@index'
            )->name('admin.ventilator_value.by_ventilator');

            Route::get(
                '/ventilator_value/{id}',
                'VentilatorValueController@asyncGetDetail'
            )->name('admin.ventilator_value.detail');

            Route::get(
                '/ventilator_values',
                'VentilatorValueController@asyncSearch'
            )->name('admin.ventilator_value.search');
        });

        Route::group(['middleware' => ['can:ventilator_value_editable']], function () {

            Route::put(
                '/ventilator_value',
                'VentilatorValueController@asyncUpdate'
            )->name('admin.ventilator_value.update');

            Route::delete(
                '/ventilator_value',
                'VentilatorValueController@asyncBulkDelete'
            )->name('admin.ventilator_value.bulk_delete');
        });

        /**
         * 患者観察研究データ管理
         */
        Route::group(['middleware' => ['can:patient_value_readable']], function () {
            Route::get(
                '/patient_value',
                'PatientValueController@index'
            )->name('admin.patient_value.index');

            Route::get(
                '/patient_value/{id}',
                'PatientValueController@asyncGetDetail'
            )->name('admin.patient_value.detail');

            Route::get(
                '/patient_values',
                'PatientValueController@asyncSearch'
            )->name('admin.patient_value.search');

            Route::get(
                '/patient_value/async/organization_data',
                'PatientValueController@asyncDataOrganization'
            )->name('admin.patient_value.async.organization_data');
        });

        Route::group(['middleware' => ['can:patient_value_editable']], function () {

            Route::put(
                '/patient_value',
                'PatientValueController@asyncUpdate'
            )->name('admin.patient_value.update');

            Route::delete(
                '/patient_value',
                'PatientValueController@asyncLogicalDelete'
            )->name('admin.patient_value.logical_delete');
        });
    });
});
