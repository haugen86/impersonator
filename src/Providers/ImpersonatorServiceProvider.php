<?php

namespace Naust\Impersonator\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Naust\Impersonator\Http\Middleware\VerifyUserIsAdminOrDeveloper;

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
        if (!$this->app->routesAreCached()) {
            Route::group([
                'namespace' => 'Naust\Impersonator\Http\Controllers\Backend',
                'middleware' => ['web'],
            ], function ($router) {
                require __DIR__.'/../Http/backendRoutes.php';
            });
        }
    }

    public function definePublishing()
    {
        // Configs
        $this->publishes([
            __DIR__.'/../../resources/config/impersonator.php' => config_path('impersonator.php'),
        ], 'impersonator-config');
    }
}
