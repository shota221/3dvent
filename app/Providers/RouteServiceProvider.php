<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->namespace = 'App\Http\Controllers';

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $host = app()->getTld();

        // apiルーティング
        Route::prefix('')
            ->domain('api.' . $host)
            ->middleware('api')
            ->namespace($this->namespace . '\Api')
            ->group(base_path('routes/api.php'));

        // // adminルーティング
        // Route::prefix('')
        //     ->domain('admin.' . $host)
        //     ->middleware('admin')
        //     ->namespace($this->namespace . '\Admin')
        //     ->group(base_path('routes/admin.php'));

        
    }
}
