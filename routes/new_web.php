<?php

use App\Http\Controllers\Admin_api\CommentsController;
use App\Http\Controllers\New_web\MainController;
use App\Http\Controllers\New_web\SitemapXmlController;

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

Route::get('/', [MainController::class, 'index'])->name('home_route');

Route::get('get-page-comments/{page_id}', [CommentsController::class, 'getPageComments']);

Route::middleware('auth')->group(function () {
    Route::post('save-comment', [CommentsController::class, 'store']);
});

Route::get('/sitemap.xml', [SitemapXmlController::class, 'index']);
Route::get('/feed', [SitemapXmlController::class, 'index']);

Route::get('/blog/{slug}', [MainController::class, 'page']);
Route::get('/{slug}', [MainController::class, 'page'])->name('new_general_page');
