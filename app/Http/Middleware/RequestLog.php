<?php

namespace App\Http\Middleware;

use Closure;

class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // FOR DEBUG //
        if (config('app.debug')) {
            $this->writeLog($request);
        }

        return $next($request);
    }

    private function writeLog($request)                                                                                                 
    {
        $query = collect($request->all())->except("q")->all();

        \Log::debug(
            'ーーリクエスト処理 STARTーー' . "\n" 
            . 'URL=' . url()->current() . "\n" 
            . 'METHOD=' . $request->method() . "\n" 
            . 'ACTION=' . $request->route()->getActionName() . "\n", 
            [ 
                'BODY' => urldecode(http_build_query($query))
            ]
        );
    }
}
