<?php

use Illuminate\Support\Facades\Auth;

use App\Http\Auth\UserTokenGuard;

if (! function_exists('route_path')) {
    
    /**
     * ルート名から相対パスを取得
     * 
     * @param  [type] $name       [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    function route_path($name, $parameters = [])
    {
        return route($name, $parameters, false);
    }
}

if (! function_exists('guess_route_name')) {
    
    /**
     * 現在のルートタイプより
     * 曖昧ルート名から実際のルート名を取得
     * 
     * @param  [type] $name       [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    function guess_route_name($name)
    {
        $routeType = app()->getRouteType();

        return $routeType . '.' . $name;
    }
}

if (! function_exists('guess_route')) {
    
    /**
     * 現在のルートタイプより
     * 曖昧ルート名からFULLパスを取得
     * 
     * @param  [type] $name       [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    function guess_route($name, $parameters = [])
    {
        return route(guess_route_name($name), $parameters);
    }
}

if (! function_exists('guess_route_path')) {
    
    /**
     * 現在のルートタイプより
     * 曖昧ルート名から相対パスを取得
     * 
     * @param  [type] $name       [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    function guess_route_path($name, $parameters = [])
    {
        return route(guess_route_name($name), $parameters, false);
    }
}

if (! function_exists('trans_code')) {
    
//     *
//      * message keyをmessage codeに変換
//      * 
//      * @see config/message_code.php
//      * 
//      * @param  string $key [description]
//      * @return [type]      [description]
     
    function trans_code(string $key)
    {
        $messageCodes = app('config')->get('message_code');

        if (isset($messageCodes[$key])) return $messageCodes[$key];

        return null;
    }
}

if (! function_exists('is_current_route')) {

    /**
     * 現在のアクセスURLチェック
     * 
     * @param  [type]  $name [description]
     * @return boolean       [description]
     */
    function is_current_route($regix)
    {
        return request()->routeIs($regix);
    }
}
