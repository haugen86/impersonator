<?php
/**
 * Created by PhpStorm.
 * User: haugen
 * Date: 01.06.2017
 * Time: 14.12
 */

namespace Lx3\Impersonator\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Lx3\Impersonator\Http\Middleware\VerifyUserIsAdminOrDeveloper;

class ImpersonatorServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        $router->aliasMiddleware('dev', VerifyUserIsAdminOrDeveloper::class);
        $this->defineRoutes();
        $this->definePublishing();
    }

    public function defineRoutes()
    {
        // If the routes have not been cached, we will include them in a route group
        // so that all of the routes will be conveniently registered to the given
        // controller namespace. After that we will load the SMS routes file.
        if (!$this->app->routesAreCached()) {
            Route::group([
                'namespace'  => 'Lx3\Impersonator\Http\Controllers\Backend',
                'middleware' => ['web']
            ], function ($router) {
                require __DIR__ . '/../Http/backendRoutes.php';
            });

            Route::group([
                'namespace'  => 'Lx3\Impersonator\Http\Controllers\Frontend',
                'middleware' => ['web']
            ], function ($router) {
                require __DIR__ . '/../Http/frontendRoutes.php';
            });
        }
    }

    public function definePublishing()
    {
        // Configs
        $this->publishes([
            __DIR__ . '/../../resources/config/impersonator.php' => config_path('impersonator.php')
        ], 'impersonator-config');
    }
}