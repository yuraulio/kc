<?php

use App\Http\Controllers\Admin\KnowledgeController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::domain('knowledge.' . env('APP_DOMAIN'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name("admin-login");
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name("admin-authenticate");

    Route::group(['middleware' => ['auth:admin_web']], function () {
        Route::get('/', [KnowledgeController::class, 'index']);
        Route::get('/knowledge/{slug}', [KnowledgeController::class, 'article']);
    });
});
