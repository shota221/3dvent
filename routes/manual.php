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
    // TODO　テストページ　後で消す
    Route::get('/test', 'ManualController@test')->name('manual.test');

});