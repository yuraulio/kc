<?php

use App\Http\Controllers\new_web\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix('v2')->group(function () {
    Route::get('/', [MainController::class, 'index']);
    Route::get('/blog/{slug}', [MainController::class, 'blogPost']);
});
