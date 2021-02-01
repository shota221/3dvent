<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Route制御ミドルウェア
 * 
 * allowHttpRouteType以外のルートにはアクセス不可
 * 
 * routes/**.phpファイルにて利用
 */
class HandleRouteByHttpRouteType  {

    /**
     *
     * @var \Illuminate\Contracts\Foundation\Application 
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     * 
     * @param  [type]  $request            [description]
     * @param  Closure $next               [description]
     * @param  [type]  $allowHttpRouteType [description]
     * @return [type]                      [description]
     */
    public function handle($request, Closure $next, $allowHttpRouteType = null)
    {
        $allowHttpRouteType = ucfirst($allowHttpRouteType ?? '');

        $method = "isHttpRouteType{$allowHttpRouteType}";

        if (is_null($allowHttpRouteType) || $this->app->$method()) {
            return $next($request);
        } 

        throw new NotFoundHttpException();        
    }



}