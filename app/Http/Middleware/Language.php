<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories as Repos;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Language
{
    const ACCEPT_LANGUAGE_HEADER = 'Accept-Language';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //APIルートの場合、ヘッダーを見て言語コード取得。それ以外の場合はクッキーから取得
        if (app()->isHttpRouteTypeApi()) {
            $language_code = $request->header(self::ACCEPT_LANGUAGE_HEADER);
            $key_exists    = array_key_exists($language_code, config('languages'));
            if ($key_exists) {
                App::setLocale($language_code);
            } else {
                App::setLocale(config('app.fallback_locale'));
            }
        } else if(app()->isHttpRouteTypeManual()) {
            $uri           = rtrim($_SERVER["REQUEST_URI"], '/');
            $language_code = substr($uri, strrpos($uri, '/') + 1);
            // クエリパラメータがあれば削除
            $language_code = preg_replace('/\?.+$/', '', $language_code);
            $key_exists    = array_key_exists($language_code, config('languages'));

            if ($key_exists) {
                App::setLocale($language_code);
            } else {
                App::setLocale(config('app.fallback_locale'));
            }

        } else {
            $language_key  = config('cookie.language_key');
            $language_code = $request->cookie($language_key);
            $key_exists    = array_key_exists($language_code, config('languages'));

            if ($key_exists) {
                App::setLocale($language_code);
            } else {
                if (!is_null(Auth::user())) {
                    $organization_id = Auth::user()->organization_id;
                    $language_code   = Repos\OrganizationRepository::getLocaleById($organization_id);

                    App::setLocale($language_code);
                } else {
                    App::setLocale(config('app.fallback_locale'));
                }
            }
        }
        return $next($request);
    }
}
