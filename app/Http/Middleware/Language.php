<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories as Repos;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        $language_key  = config('cookie.language_key');
        $language_code = $request->cookie($language_key);
        $key_exists    = array_key_exists($language_code, config('languages')); 

        if ($key_exists) {
            App::setLocale($language_code);
        } else {
            if (! is_null(Auth::user())) {
                $organization_id = Auth::user()->organization_id;
                $language_code   = Repos\OrganizationRepository::getLocaleById($organization_id);
                
                App::setLocale($language_code);
            } else {
                App::setLocale(config('app.fallback_locale'));
            }
        }

        return $next($request);
    }
}
