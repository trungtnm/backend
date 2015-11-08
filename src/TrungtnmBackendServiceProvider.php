<?php

namespace Trungtnm\Backend;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Sentinel;

class TrungtnmBackendServiceProvider extends ServiceProvider
{
    /**
     * namespace of backend controllers
     *
     * @var string
     */
    protected $controllerNamespace = 'Trungtnm\Backend\Http\Controller';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            $this->map();
        }

        View::addNamespace('TrungtnmBackend', __DIR__ .'/resources/views');
        $this->loadViewsFrom(base_path('resources/views/trungtnm/backend'), 'TrungtnmBackend');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'TrungtnmBackend');

        //publish views, configs
        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/trungtnm/backend'),
            __DIR__.'/config/trungtnm.backend.php' => config_path('trungtnm.backend.php'),
        ]);
        $this->publishes(
            [__DIR__.'/resources/assets' => public_path('vendor/trungtnm/backend')],
            'public'
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/trungtnm.backend.php', 'trungtnm.backend'
        );

        $this->filterPermission();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * load routes
     */
    protected function map(){
        //add routes of core modules
        $coreRouteFolder = __DIR__ . '/Http/routes';
        $this->addRoutes($this->controllerNamespace, $coreRouteFolder);

        //add routes of user defined modules
        $userControllerNamespace = config('trungtnm.backend.modules_controller_namespace');
        $userRouteFolder = config('trungtnm.backend.modules_route_path');
        if(!empty($userControllerNamespace) && !empty($userRouteFolder)){
//            $this->addRoutes($userControllerNamespace, $userRouteFolder);
        }
    }

    protected function addRoutes($controllerNamespace, $routeFolder)
    {
        Route::group([
            'namespace' => $controllerNamespace,
            'prefix' => config('trungtnm.backend.uri')
        ],
        function() use ($routeFolder) {
            // scan routes php file in route folder -> route folder should contain only Laravel routes define
            $pattern = $routeFolder . '/*.php';
            $modules = glob($pattern);
            foreach ($modules as $routerPath) {
                require $routerPath;
            }
        });
    }

    /**
     * check for permission of modules
     */
    protected function filterPermission()
    {
        Route::filter('hasAccess', function($route, $request, $permission = null)
        {
            if (Sentinel::check()) {
                if (empty($permission) || Sentinel::hasAccess([$permission])) {
                    return;
                }
            }
            if (empty($permission)) {
                return redirect(route('loginBackend'))->withErrors('Please login first');
            } else {
                return redirect(route('accessDenied'))->withErrors('Permission denied.');
            }

        });
    }
}
