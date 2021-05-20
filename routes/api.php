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
Route::group(['middleware' => ['routetype:api']], function () {

    Route::group(['middleware' => ['can:api_accessable']], function () {
        /**********
         * appkey *
         **********/
        //idfv登録・アプリキー発行
        Route::post('/appkey', 'AppkeyController@create')->name('api.appkey.create');

        /**********
         * obserbation(TRI_API) *
         **********/
        // 観察研究データ数取得
        Route::get('/observation/count', 'ObservationController@count')->name('api.observation.count');

        // 患者観察研究データ取得
        Route::get('/observation/patient/list', 'ObservationController@patientList')->name('api.observation.patient.list');
        
        // 機器観察研究データ取得
        Route::get('/observation/ventilator/list', 'ObservationController@ventilatorList')->name('api.observation.ventilator.list');


    });

    //アプリキー認証ルート
    Route::group(['middleware' => ['can:appkey_accessable']], function () {
        /*************************
         * X-User-Token不要ルート *
         *************************/

        //該当組織ユーザーがユーザートークンを有してしているかを判定
        Route::get('/auth/token', 'AuthController@check')->name('api.auth.check');

        //組織ユーザートークン発行
        Route::post('/auth/token', 'AuthController@generateToken')->name('api.auth.generate_token');


        /*************
         * calculate *
         *************/
        //各種計算
        Route::get('/calculate/default_flow', 'CalcController@defaultFlow')->name('api.calc.default_flow');
        Route::get('/calculate/estimated_data', 'CalcController@estimatedData')->name('api.calc.estimated_data');
        Route::post('/calculate/ie/manual', 'CalcController@ieManual')->name('api.calc.ie_manual');
        Route::post('/calculate/ie/sound', 'CalcController@ieSound')->name('api.calc.ie_sound');
        //音声解析テスト端末用音声データ保存API
        Route::post('/calculate/ie/sound_sampling', 'CalcController@ieSoundSampling')->name('api.calc.ie_sound_sampling');


        /***********
         * patient *
         ***********/
        //患者情報取得(未ログインユーザとログインユーザで共通の処理)
        Route::get('/patient/{id}', 'PatientController@show')->name('api.patient.show');

        //患者情報登録
        Route::post('/patient/no_auth', 'PatientController@create')->name('api.patient.create.no_auth');
        //患者情報更新
        Route::put('/patient/{id}/no_auth', 'PatientController@update')->name('api.patient.update.no_auth');


        /**************
         * ventilator *
         **************/
        //呼吸器情報取得（GS1コード読み込み）(未ログインユーザとログインユーザで共通の処理)
        Route::get('/ventilator', 'VentilatorController@show')->name('api.ventilator.show');

        //呼吸器情報登録
        Route::post('/ventilator/no_auth', 'VentilatorController@create')->name('api.ventilator.create.no_auth');


        /********************
         * ventilator_value *
         ********************/
        //機器観察研究データのリストを取得(未ログインユーザとログインユーザで共通の処理)
        Route::get('/ventilator_value', 'VentilatorValueController@list')->name('api.ventilator_value.list');
        //機器観察研究データの詳細取得(未ログインユーザとログインユーザで共通の処理)
        Route::get('/ventilator_value/{id}', 'VentilatorValueController@show')->name('api.ventilator_value.show');

        //測定時機器関連値登録
        Route::post('/ventilator_value/no_auth', 'VentilatorValueController@create')->name('api.ventilator_value.create.no_auth');


        /*************************
         * X-User-Token必須ルート *
         *************************/
        Route::group(['middleware' => ['auth:user_token']], function () {

            /********
             * auth *
             ********/
            //組織ユーザートークン失効
            Route::delete('/auth/token', 'AuthController@removeToken')->name('api.auth.remove_token');

            /***********
             * patient *
             ***********/
            //患者情報登録
            Route::post('/patient', 'PatientController@create')->name('api.patient.create');
            //患者情報更新
            Route::put('/patient/{id}', 'PatientController@update')->name('api.patient.update');
            //患者観察研究データの存在確認および取得
            Route::get('/patient/{id}/detail', 'PatientController@showDetail')->name('api.patient.show_detail');
            //患者観察研究データの登録
            Route::post('/patient/{id}/detail', 'PatientController@createDetail')->name('api.patient.create_detail');
            //患者観察研究データの更新
            Route::put('/patient/{id}/detail', 'PatientController@updateDetail')->name('api.patient.update_detail');


            /********
             * user *
             ********/
            //使用者情報取得
            Route::get('/user', 'UserController@show')->name('api.user.show');
            //使用者情報更新
            Route::put('/user', 'UserController@update')->name('api.user.update');

            /**************
             * ventilator *
             **************/
            //呼吸器情報登録
            Route::post('/ventilator', 'VentilatorController@create')->name('api.ventilator.create');
            //呼吸器情報更新
            Route::put('/ventilator/{id}', 'VentilatorController@update')->name('api.ventilator.update');


            /********************
             * ventilator_value *
             ********************/
            //測定時機器関連値登録
            Route::post('/ventilator_value', 'VentilatorValueController@create')->name('api.ventilator_value.create');
            //機器観察研究データの更新
            Route::put('/ventilator_value/{id}', 'VentilatorValueController@update')->name('api.ventilator_value.update');

            // Route::get('/test', 'TestController@index');
        });
    });
});
