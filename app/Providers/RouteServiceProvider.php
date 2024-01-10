<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     *
     * $rachow - added since was removed in framework version 8.* 
     *
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';


    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';


    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    
     // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        /** remove defaul setup.
        $this->routes(function () {
            Route::prefix('api')
        	        ->middleware('api')
        	        ->namespace($this->namespace)
        	        ->group(base_path('routes/api.php'));

            Route::middleware('web')
        	        ->namespace($this->namespace)
        	        ->group(base_path('routes/web.php'));
        });
        */
    }

    /**
     * Mapping of routes for both web and API. $rachow
     *
     * @param  none
     * @return void
    */
    public function map()
    {
        // web routes
        $this->mapWebRoutes();

        // api routes
        $this->mapApiRoutes();

        // api version 1
        $this->mapApiV1Routes();

        // api version 2
        $this->mapApiV2Routes();
    }

    /**
     * Define the "web" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Add the API routes that are accessible without versioning.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace . '\\Api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Add the api routes that are accessible to version 1.
     *
     * @return void
     */
    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace($this->namespace . '\\Api\\V1')
            ->group(base_path('routes/api/v1.php'));
    }

    /**
     * Add the api routes that are accessible to version 2.
     *
     * @return void
     */
    protected function mapApiV2Routes()
    {
        Route::prefix('api/v2')
            ->middleware('api')
            ->namespace($this->namespace . '\\Api\\V2')
            ->group(base_path('routes/api/v2.php'));
    }

    /**
    * Configure the rate limiters for the application.
    *
    * @return void
    */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
    	    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
