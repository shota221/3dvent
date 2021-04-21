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

    // TODO keshitekudasai
    Route::group(['middleware' => ['can:api_accessable']], function() {
        Route::get('/test', 'TestController@index')->name('api.test');
    });

    //トークン認証ルート
    // 2021.04.26 api_token認可実装、以下 'auth:user_token'　をmiddlewareからはずしました。認証必須ルートを分けて記述してください。
    Route::group(['middleware' => ['can:api_accessable']], function () {             
        /**********
         * appkey *
         **********/
        //idfv登録・アプリキー発行
        Route::post('/appkey','AppkeyController@create')->name('api.appkey.create');


        /********
         * auth *
         ********/        
        //組織ユーザートークン発行
        Route::put('/auth/token/generate', 'AuthController@generateToken')->name('api.auth.generate_token');
        //組織ユーザートークン失効
        Route::put('/auth/token/remove', 'AuthController@removeToken')->name('api.auth.remove_token');


        /*************
         * calculate *
         *************/        
        //各種計算
        Route::get('/calculate/default_flow','CalcController@defaultFlow')->name('api.calc.default_flow');
        Route::get('/calculate/estimated_data','CalcController@estimatedData')->name('api.calc.estimated_data');
        Route::post('/calculate/ie/manual','CalcController@ieManual')->name('api.calc.ie_manual');
        Route::post('/calculate/ie/sound','CalcController@ieSound')->name('api.calc.ie_sound');
        //音声解析テスト端末用音声データ保存API
        Route::post('/calculate/ie/sound_sampling','CalcController@ieSoundSampling')->name('api.calc.ie_sound_sampling');


        /***********
         * patient *
         ***********/
        //患者情報登録
        Route::post('/patient', 'PatientController@create')->name('api.patient.create');
        //患者情報取得
        Route::get('/patient/{id}', 'PatientController@show')->name('api.patient.show');
        //患者情報更新
        Route::put('/patient/{id}', 'PatientController@update')->name('api.patient.update');
        //患者観察研究データの存在確認および取得
        Route::get('/patient/{id}/detail','PatientController@showDetail')->name('api.patient.show_detail');
        //患者観察研究データの登録
        Route::post('/patient/{id}/detail','PatientController@createDetail')->name('api.patient.create_detail');
        //患者観察研究データの更新
        Route::put('/patient/{id}/detail','PatientController@updateDetail')->name('api.patient.update_detail');        


        /********
         * user *
         ********/
        //使用者情報取得
        Route::get('/user','UserController@show')->name('api.user.show');
        //使用者情報更新
        Route::put('/user','UserController@update')->name('api.user.update');

         
        /**************
         * ventilator *
         **************/
        //呼吸器情報取得（GS1コード読み込み）
        Route::get('/ventilator','VentilatorController@show')->name('api.ventilator.show');
        //呼吸器情報登録
        Route::post('/ventilator','VentilatorController@create')->name('api.ventilator.create');
        //測定時機器関連値取得
        Route::get('/ventilator/{id}','VentilatorController@showValue')->name('api.ventilator.show_value');
        //測定時機器関連値登録
        Route::post('/ventilator/{id}','VentilatorController@createValue')->name('api.ventilator.create_value');
        //該当機器の最新のventilator_valueレコードに最終設定フラグ付与
        Route::put('/ventilator/{id}','VentilatorController@updateValue')->name('api.ventilator.update_value');

        
        /********************
         * ventilator_value *
         ********************/
        //機器観察研究データのリストを取得
        Route::get('/ventilator_value/list','VentilatorController@showValueList')->name('api.ventilator.show_value_list');
        //機器観察研究データの詳細取得
        Route::get('/ventilator_value','VentilatorController@showDetailValue')->name('api.ventilator.show_detail_value');
        //機器観察研究データの更新
        Route::put('/ventilator_value','VentilatorController@updateDetailValue')->name('api.ventilator.update_detail_value');

    });

});