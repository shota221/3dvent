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

Route::group(['middleware' => ['routetype:form']], function () {
    Route::get('/', 'OrganizationRegistrationController@index')->name('form.organization_registration');

    Route::post('/', 'OrganizationRegistrationController@create')->name('form.organization_registration.create');
});
