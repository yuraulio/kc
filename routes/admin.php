<?php

use App\Http\Controllers\Admin\Dashboard;
use Illuminate\Support\Facades\Route;

Route::domain('admin.' . env('APP_URL'))->group(function () {
    Route::group(['middleware' => 'auth.aboveauthor'], function () {
        Route::get('/', [Dashboard::class, 'index'])->name("admin-adshboard");
    });
});
