<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin_api\ExportController;
use App\Http\Controllers\Admin_api\MenuController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

//Route::domain('admin.' . config('app.app_domain'))->group(function () {
Route::domain(config('app.prefix_new_admin') . config('app.app_domain'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name('admin-login');
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin_authenticate');

    Route::group(['middleware' => ['auth:admin_web']], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('admin-dashboard');
        Route::get('/categories', [DashboardController::class, 'categories'])->name('admin-categories');
        Route::get('/templates', [DashboardController::class, 'templates'])->name('admin-templates');
        Route::get('/pages', [DashboardController::class, 'pages'])->name('admin-pages');
        Route::get('/pages_blog', [DashboardController::class, 'pages'])->name('admin-pages-blog');
        Route::get('/pages_knowledge', [DashboardController::class, 'pages'])->name('admin-pages-knowledge');
        Route::get('/comments', [DashboardController::class, 'comments'])->name('admin-comments');

        Route::get('/media', [DashboardController::class, 'media'])->name('admin-media');
        Route::get('/menus', [DashboardController::class, 'menu'])->name('admin-menu');
        Route::get('/menus', [DashboardController::class, 'menu'])->name('admin-menu');
        Route::get('/ticker', [DashboardController::class, 'ticker'])->name('admin-ticker');

        Route::get('/countdown/{id}', [DashboardController::class, 'countdownEdit']);
        Route::get('/countdown', [DashboardController::class, 'countdown'])->name('admin-countdown');
        Route::get('/new_countdown', [DashboardController::class, 'countdownNew']);

        Route::get('/reports', [DashboardController::class, 'reports'])->name('admin-reports');

        Route::prefix('users')->group(function () {
            Route::get('/admins', [UserController::class, 'admins'])->name('admins-management');
        });

        Route::get('/page/{id}', [DashboardController::class, 'pageEdit']);
        Route::get('/new_page', [DashboardController::class, 'pageNew']);

        Route::get('/template/{id}', [DashboardController::class, 'templateEdit']);
        Route::get('/new_template', [DashboardController::class, 'templateNew']);

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        Route::get('/export', [ExportController::class, 'subscriptionEnd'])->name('export');

        Route::get('/royalties', [DashboardController::class, 'royalties'])->name('royalties');
        Route::get('/royalties/{id}', [DashboardController::class, 'royaltiesShow']);
    });
});

Route::group(['middleware' => ['auth:admin_web']], function () {
    Route::get('menus', '\CodexShaper\Menu\Http\Controllers\MenuController@index');
    Route::get('menus/builder/{id}', '\CodexShaper\Menu\Http\Controllers\MenuItemController@showMenuItems')->name('menu.builder');
    /*
            * Helpers Route
            */
    Route::get('assets', '\CodexShaper\Menu\Http\Controllers\MenuController@assets')->name('menu.asset');

    /*
        * Vue Routes
        */
    // Menus
    Route::get('getMenus', '\CodexShaper\Menu\Http\Controllers\MenuController@getMenus');
    Route::get('menu/{id}', '\CodexShaper\Menu\Http\Controllers\MenuController@getMenu');
    Route::get('menu/html/{id}', '\CodexShaper\Menu\Http\Controllers\MenuController@getMenuHtml');
    Route::post('menu', '\CodexShaper\Menu\Http\Controllers\MenuController@store');
    Route::post('menu/sort', '\CodexShaper\Menu\Http\Controllers\MenuController@sort');
    Route::put('menu', '\CodexShaper\Menu\Http\Controllers\MenuController@update');
    Route::delete('menu/{id}', '\CodexShaper\Menu\Http\Controllers\MenuController@destroy');
    Route::post('menu/clone/{id}', [MenuController::class, 'clone']);
    // Menu Items
    Route::get('menu/items/{menu_id}', '\CodexShaper\Menu\Http\Controllers\MenuItemController@getMenuItems');
    Route::get('menu/{menu_id}/item/{id}', '\CodexShaper\Menu\Http\Controllers\MenuItemController@getMenuItem');
    Route::post('menu/item/sort', '\CodexShaper\Menu\Http\Controllers\MenuItemController@sort');
    Route::post('menu/item', '\CodexShaper\Menu\Http\Controllers\MenuItemController@store');
    Route::put('menu/item', '\CodexShaper\Menu\Http\Controllers\MenuItemController@update');
    Route::delete('/menu/item/{id}', '\CodexShaper\Menu\Http\Controllers\MenuItemController@destroy');
    // Menu Settings
    Route::post('menu/item/settings', '\CodexShaper\Menu\Http\Controllers\MenuItemController@storeSettings');
    Route::get('menu/item/settings/{menu_id}', '\CodexShaper\Menu\Http\Controllers\MenuItemController@getSettings');
});
