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

    //トークン認証ルート
    Route::group(['middleware' => ['auth:api_token,user_token']], function () {
        // TODO delete me
        Route::get('/auth_test', 'AuthTestController@index')->name('api.test.auth');
        
        //idfv登録・アプリキー発行
        Route::post('/appkey','AppkeyController@create')->name('api.appkey.create');

        //組織ユーザートークン発行
        Route::post('/auth/login', 'AuthController@login')->name('api.auth.login');

        //組織ユーザートークン失効
        Route::post('/auth/logout', 'AuthController@logout')->name('api.auth.logout');

        //各種計算
        Route::get('/calculate/default_flow','CalcController@defaultFlow')->name('api.calc.default_flow');
        Route::get('/calculate/estimated_data','CalcController@estimatedData')->name('api.calc.estimated_data');
        Route::post('/calculate/ie/manual','CalcController@ieManual')->name('api.calc.ie_manual');
        Route::post('/calculate/ie/sound','CalcController@ieSound')->name('api.calc.ie_sound');

        //患者情報登録
        Route::post('/patient', 'PatientController@create')->name('api.patient.create');
        //患者情報取得
        Route::get('/patient/{id}', 'PatientController@show')->name('api.patient.show');
        //患者情報更新
        Route::put('/patient/{id}', 'PatientController@update')->name('api.patient.update');

        //呼吸器情報取得（GS1コード読み込み）
        Route::get('/ventilator','VentilatorController@show')->name('api.ventilator.show');
        //呼吸器情報登録
        Route::post('/ventilator','VentilatorController@create')->name('api.ventilator.create');
        //機器関連値取得
        Route::get('/ventilator/{id}','VentilatorController@showValue')->name('api.ventilator.show_value');
        //機器関連値登録
        Route::post('/ventilator/{id}','VentilatorController@createValue')->name('api.ventilator.create_value');
        //機器関連値更新
        Route::put('/ventilator/{id}','VentilatorController@updateValue')->name('api.ventilator.update_value');

        //音声解析テスト端末用音声データ保存API
        Route::post('/calculate/ie/sound_sampling','CalcController@ieSoundSampling')->name('api.calc.ie_sound_sampling');
    });

});