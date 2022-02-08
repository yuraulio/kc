<?php

use App\Http\Controllers\Admin_api\CommentsController;
use App\Http\Controllers\Admin_api\PagesController;
use App\Http\Controllers\Admin_api\MediaController;
use App\Http\Controllers\Admin_api\DashboardController;

use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::prefix('get_widget_data')->group(function () {
            Route::get('users', [DashboardController::class, 'get_widget_data_users']);
            Route::get('admins', [DashboardController::class, 'get_widget_data_admins']);
            Route::get('instructors', [DashboardController::class, 'get_widget_data_instructors']);
            Route::get('students', [DashboardController::class, 'get_widget_data_students']);
            Route::get('graduates', [DashboardController::class, 'get_widget_data_graduates']);
            Route::get('comments', [DashboardController::class, 'get_widget_comments']);
            Route::get('pages', [DashboardController::class, 'get_widget_pages']);
        });

        // categories
        Route::resource('categories', Admin_api\CategoriesController::class)->only([
            'index', 'store', 'update', 'show', 'destroy'
        ]);

        // templates
        Route::resource('templates', Admin_api\TemplatesController::class)->only([
            'index', 'store', 'update', 'show', 'destroy'
        ]);

        // pages
        Route::resource('pages', Admin_api\PagesController::class)->only([
            'index', 'store', 'update', 'show', 'destroy'
        ]);

        // comments
        Route::resource('comments', Admin_api\CommentsController::class)->only([
            'index', 'destroy'
        ]);

        Route::put('pages/update_published/{id}', [PagesController::class, 'updatePublished']);
        Route::post('pages/upload_image', [PagesController::class, 'uploadImage']);
        Route::resource('media_manager', Admin_api\MediaController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::post('media_manager/upload_image', [MediaController::class, 'uploadImage']);
        Route::get('media_manager/files', [MediaController::class, 'files']);
    });
});
