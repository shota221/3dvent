<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;

class HandleViewPath {

    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    private $viewResourcePath;

    private $rootNamespace;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;

        $this->viewResourcePath = app()['config']['view.paths'][0];

        $this->rootNamespace = 'App\Http\Controllers';
    }

    /**
     * Handle an incoming request.
     * 
     * view探索パスを設定
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$prefix = $request->route()->getPrefix();

        $contollerPathName = $this->getContollerViewPathName($request->route()->getController());

        $location = $this->viewResourcePath . $contollerPathName;

        // 優先的に検索するパスを設定
        $this->view->getFinder()->prependLocation($location);

        return $next($request);
    }


    /**
     * コントローラ名からVIEWの探索パス名を生成
     * 
     * ex) App\\Http\\Controllers\\Admin\\Game\\CheckinControler -> Admin/Game/Checkin/
     * 
     * @param  [type] $contollerInstance [description]
     * @return [type]                    [description]
     */
    private function getContollerViewPathName($contollerInstance)
    {
        return str_replace(
            [
                $this->rootNamespace, 
                '\\', 
                'Controller'
            ], [
                '', 
                DIRECTORY_SEPARATOR, 
                ''
            ], 
            get_class($contollerInstance)
        );
    }

}