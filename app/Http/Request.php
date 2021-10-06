<?php 

namespace App\Http;

use Illuminate\Http\Request as BaseRequest;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

use App\Http\Auth\UserTokenGuard;

class Request extends BaseRequest
{
    /**
     * 認証トークンが含まれているか
     * 
     * ヘッダ(Bearer)、インプット、クエリ、セッションのいづれかに存在する
     * 
     * @return boolean [description]
     */
    public function hasUserToken()
    {
        return collect(config('auth.guards'))
            ->filter(function($setting, $guardName) {
                $guard = Auth::guard($guardName);

                return $guard instanceof UserTokenGuard && ! empty($guard->getTokenForRequest());    
            })
            ->isNotEmpty();
    }   
}