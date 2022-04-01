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
if (!session()->has('404redirect')) {
    Route::get('get-page-comments/{page_id}', [CommentsController::class, 'getPageComments']);

    Route::middleware('auth')->group(function () {
        Route::post('save-comment', [CommentsController::class, 'store']);
    });

    // Route::prefix('v2')->group(function () {
    // Route::get('/', [MainController::class, 'index']);
    Route::get('/sitemap.xml', [SitemapXmlController::class, 'index']);
    Route::get('/feed', [SitemapXmlController::class, 'index']);

    Route::get('/blog/{slug}', [MainController::class, 'page']);
    Route::get('/{slug}', [MainController::class, 'page']);
    // });
}
