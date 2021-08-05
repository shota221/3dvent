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
});
