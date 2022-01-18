<?php

use App\Http\Controllers\Admin_api\Categories;
use App\Http\Controllers\Admin_api\Pages;
use App\Http\Controllers\Admin_api\Templates;
use App\Http\Controllers\Admin_api\Dashboard;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_URL'))->group(function () {
    Route::get('get_widget_data/users', [Dashboard::class, 'get_widget_data_users']);
    Route::get('get_widget_data/admins', [Dashboard::class, 'get_widget_data_admins']);
    Route::get('get_widget_data/instructors', [Dashboard::class, 'get_widget_data_instructors']);
    Route::get('get_widget_data/students', [Dashboard::class, 'get_widget_data_students']);
    Route::get('get_widget_data/graduates', [Dashboard::class, 'get_widget_data_graduates']);

    // categories
    Route::get('categories', [Categories::class, 'list']);
    Route::post('categories/add', [Categories::class, 'add']);
    Route::post('categories/edit/{id}', [Categories::class, 'edit']);
    Route::delete('categories/delete/{id}', [Categories::class, 'delete']);
    Route::get('categories/get/{id}', [Categories::class, 'get']);

    // templates
    Route::get('templates', [Templates::class, 'list']);
    Route::post('templates/add', [Templates::class, 'add']);
    Route::post('templates/edit/{id}', [Templates::class, 'edit']);
    Route::delete('templates/delete/{id}', [Templates::class, 'delete']);
    Route::get('templates/get/{id}', [Templates::class, 'get']);

    // pages
    Route::get('pages', [Pages::class, 'list']);
    Route::post('pages/add', [Pages::class, 'add']);
    Route::post('pages/edit/{id}', [Pages::class, 'edit']);
    Route::post('pages/delete/{id}', [Pages::class, 'delete']);
    Route::get('pages/get/{id}', [Pages::class, 'get']);
    Route::put('pages/update_published/{id}', [Pages::class, 'updatePublished']);
});
