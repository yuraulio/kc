<?php
use App\Http\Controllers\New_web\MainController;

Route::get('/', [MainController::class, 'index'])->name('homepage');
Route::get('/{slug}', [MainController::class, 'page'])->name('new_general_page');
