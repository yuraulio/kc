<?php

use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_URL'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name("admin-login");
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name("admin-authenticate");

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [Dashboard::class, 'index'])->name("admin-dashboard");
    });
});
