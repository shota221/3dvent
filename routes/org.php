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
    //TODO:DELETE ME
    Route::get('/test','TestController@index')->name('org.test');
});