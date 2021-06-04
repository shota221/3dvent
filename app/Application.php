<?php

namespace App;

use Illuminate\Foundation\Application as BaseApplication;

use Illuminate\Contracts\Http\Kernel as HttpKernelContract;

/**
 * アプリケーションクラス拡張
 * 
 * アクセスタイプによる環境設定ハンドル
 */
class Application extends BaseApplication
{
    /**
     * HTTPアクセス種類
     * null in console 
     *
     * @var array string
     */
    private $httpRouteType;

    /**
     * Create a new Illuminate application instance.
     * 
     * @param [type] $basePath      [description]
     * @param [type] $httpRouteType [description]
     */
    public function __construct($basePath, $httpRouteType)
    {
        parent::__construct($basePath);
        
        if (! $this->runningInConsole()) { 
            // http route type設定
            switch ($httpRouteType) {
                case 'api'      :
                case 'manual'      :
                    $this->httpRouteType = $httpRouteType; 

                    break;
                default:
                    throw new \Exception(
                        'APP_HTTP_ROUTE_TYPE環境変数が設定されていません。getenv(APP_HTTP_ROUTE_TYPE)=' . ($httpRouteType ? $httpRouteType : 'NULL')
                    );
            }
        }
    }

    public function getTld()
    {
        return parse_url(config('app.url'))['host'];
    }

    public function isHttpRouteTypeApi()
    {
        return 'api' === $this->httpRouteType;
    }

    public function isHttpRouteTypeManual()
    {
        return 'manual' === $this->httpRouteType;
    }


    
  
    public function getRouteType()
    {
        return $this->httpRouteType;
    }
}
