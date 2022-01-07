<?php

use App\Http\Controllers\Admin_api\Dashboard;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_URL'))->group(function () {
    Route::get('get_widget_data/users', [Dashboard::class, 'get_widget_data_users']);
    Route::get('get_widget_data/admins', [Dashboard::class, 'get_widget_data_admins']);
    Route::get('get_widget_data/instructors', [Dashboard::class, 'get_widget_data_instructors']);
    Route::get('get_widget_data/students', [Dashboard::class, 'get_widget_data_students']);
    Route::get('get_widget_data/graduates', [Dashboard::class, 'get_widget_data_graduates']);
});
