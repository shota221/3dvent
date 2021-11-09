<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $language_key = config('session.language_key');
        $applocale    = Session::get($language_key);
        $key_exists   = array_key_exists($applocale, config('languages')); 

        if ($key_exists) {
            App::setLocale($applocale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}
