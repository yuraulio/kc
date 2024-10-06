<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\New_web\MainController;

Route::pattern('domain', '(' . implode('|', explode(',', config('app.app_domain'))) . ')');
Route::group(['domain' => config('app.prefix_new_admin').'{domain}'], function () {
    Route::get('/__preview/{uuid}', [DashboardController::class, 'page']);

    Route::get('/', [MainController::class, 'index'])->name('homepage');
    Route::get('/{slug}', [MainController::class, 'page'])->name('new_general_page');
});
