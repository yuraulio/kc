<?php

use App\Http\Controllers\Admin_api\PagesController;
use App\Http\Controllers\Admin_api\TemplatesController;
use App\Http\Controllers\Admin_api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('get_widget_data/users', [DashboardController::class, 'get_widget_data_users']);
        Route::get('get_widget_data/admins', [DashboardController::class, 'get_widget_data_admins']);
        Route::get('get_widget_data/instructors', [DashboardController::class, 'get_widget_data_instructors']);
        Route::get('get_widget_data/students', [DashboardController::class, 'get_widget_data_students']);
        Route::get('get_widget_data/graduates', [DashboardController::class, 'get_widget_data_graduates']);

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

        Route::put('pages/update_published/{id}', [PagesController::class, 'updatePublished']);
        Route::post('pages/upload_image', [PagesController::class, 'uploadImage']);
    });
});
