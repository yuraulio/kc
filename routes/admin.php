<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name("admin-login");
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name("admin-authenticate");

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name("admin-dashboard");
        Route::get('/categories', [DashboardController::class, 'categories'])->name("admin-categories");
        Route::get('/templates', [DashboardController::class, 'templates'])->name("admin-templates");
        Route::get('/pages', [DashboardController::class, 'pages'])->name("admin-pages");
        Route::get('/comments', [DashboardController::class, 'comments'])->name("admin-comments");

        Route::get('/new_page/{uuid}', [DashboardController::class, 'page'])->name("new-page");
        Route::get('/media', [DashboardController::class, 'media'])->name("admin-media");
        Route::get('/menus', [DashboardController::class, 'menu'])->name("admin-menu");
    });
});

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
