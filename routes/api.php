<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
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
 *   Route::get('auth', 'AuthController@index')->name('api.auth');
 */
Route::group(['middleware' => ['routetype:api']], function() {

    //組織ユーザートークン発行
    Route::post('/auth/login', 'AuthController@login')->name('api.auth.login');

    //組織ユーザートークン失効
    Route::post('/auth/logout', 'AuthController@logout')->name('api.auth.logout');

    //各種計算
    Route::get('/calculate/default_flow','CalcController@defaultFlow')->name('api.calc.default_flow');
    Route::get('/calculate/estimated_data','CalcController@estimatedData')->name('api.calc.estimated_data');
    Route::post('/calculate/ie/manual','CalcController@ieManual')->name('api.calc.default_flow');
    Route::post('/calculate/ie/sound','CalcController@defaultFlow')->name('api.calc.default_flow');

    //患者情報登録
    Route::post('/patient', 'PatientController@create')->name('api.patient.create');
    //患者情報取得
    Route::get('/patient/{id}', 'PatientController@read')->name('api.patient.read');
    //患者情報更新
    Route::put('/patient/{id}', 'PatientController@update')->name('api.auth.login');

    //呼吸器情報取得（GS1コード読み込み）
    Route::get('/ventilator','VentilatorController@read')->name('api.ventilator.read');
    //呼吸器関連情報登録（呼吸器情報+機器関連値）
    Route::post('/ventilator','VentilatorController@create')->name('api.ventilator.create');
    //機器関連値取得
    Route::get('/ventilator/{id}','VentilatorController@readValues')->name('api.ventilator.read_values');
    //機器関連値更新
    Route::put('/ventilator/{id}','VentilatorController@updateValues')->name('api.ventilator.update_values')

});