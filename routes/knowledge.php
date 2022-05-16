<?php

use App\Http\Controllers\Admin\KnowledgeController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::domain('knowledge.' . env('APP_DOMAIN'))->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name("knowledge-login");
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name("knowledge-authenticate");

    Route::group(['middleware' => ['auth:admin_web']], function () {
        Route::get('/', [KnowledgeController::class, 'index']);
        Route::get('/knowledge', [KnowledgeController::class, 'index']);
        Route::get('/knowledge_search', [KnowledgeController::class, 'searchResults']);
        Route::get('/knowledge/{slug}', [KnowledgeController::class, 'article']);
    });
});
