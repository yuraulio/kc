<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\New_web\MainController;

Route::domain(config('app.app_domain'))->group(function () {
    Route::get('/__preview/{uuid}', [DashboardController::class, 'page']);

    Route::get('/', [MainController::class, 'index'])->name('homepage');
    Route::get('/{slug}', [MainController::class, 'page'])->name('new_general_page');
});
