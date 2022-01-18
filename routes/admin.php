<?php

use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name("admin-login");
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name("admin-authenticate");

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [Dashboard::class, 'dashboard'])->name("admin-dashboard");
        Route::get('/categories', [Dashboard::class, 'categories'])->name("admin-categories");
        Route::get('/templates', [Dashboard::class, 'templates'])->name("admin-templates");
        Route::get('/pages', [Dashboard::class, 'pages'])->name("admin-pages");
    });

    Route::get('/new_page/{id}', [Dashboard::class, 'page'])->name("new-page");
});


