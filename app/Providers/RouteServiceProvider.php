<?php

namespace App\Providers;

use App\Library\Cache;
use App\Model\Admin\Setting;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapAdminApiRoutes();

        $this->mapKnowelageRoutes();

        if (Cache::getCmsMode() == Setting::NEW_PAGES) {
            // If path is cached that means it doesn't exists on new website
            // Because of that we will skip loading web routes
            if (!cache(request()->path())) {
                $this->mapNewWebRoutes();
            }
        }

        $this->mapApiRoutes();
        $this->mapWebRoutes();

        $this->mapGeneralRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
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
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "knowelage" routes for the application.
     *
     *
     * @return void
     */
    protected function mapKnowelageRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/knowelage.php'));
    }

    /**
     * Define the "admin api" routes for the application.
     *
     *
     * @return void
     */
    protected function mapAdminApiRoutes()
    {
        Route::middleware('web')
            ->prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin_api.php'));
    }

    /**
     * Define the "new_web" routes for the application.
     *
     *
     * @return void
     */
    protected function mapNewWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/new_web.php'));
    }

    /**
     * Define the "general" routes for the application.
     *
     *
     * @return void
     */
    protected function mapGeneralRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/general.php'));
    }
}
