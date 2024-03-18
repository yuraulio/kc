<?php

use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // User
    //Route::get('user/{user}/edit', [UserController::class, 'edit']);
    Route::get('myprofile', [UserController::class, 'index']);
    Route::post('myprofile/update', [UserController::class, 'update']);
    Route::get('myprofile/events', [UserController::class, 'events']);

    //
    Route::post('lesson/save_note', [LessonController::class, 'saveNote']);
    Route::post('lesson/save_video_progress', [LessonController::class, 'saveVideoProgress']);
    Route::post('lesson/update_is_new', [LessonController::class, 'updateVideoIsNew']);

    //SMS
    Route::post('smsVerification', [UserController::class, 'smsVerification']);
    Route::post('getSMSVerification', [UserController::class, 'getSMSVerification']);

    // Logout
    Route::post('logout', [PassportAuthController::class, 'logout']);

    //Absences
    Route::post('absences-store', 'Api\AbsenceController@store');

    //GetDropboxToken
    Route::get('get-dropbox-token', [UserController::class, 'getDropBoxToken']);
});

Route::post('/myaccount/reset', 'Api\ForgotPasswordController@sendResetLinkEmail');

Route::domain(config('app.prefix_new_admin') . config('app.app_domain'))->group(function () {
    Route::group(['middleware' => ['auth:admin_api_api'], 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
        Route::post('medias/upload-image', \App\Http\Controllers\Api\v1\Media\UploadImageController::class);
        Route::post('medias/edit-image', \App\Http\Controllers\Api\v1\Media\EditImageController::class);
    });
});

Route::group(['middleware' => ['auth:api', 'auth.aboveauthor'], 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::post('transactions/participants/statistics', \App\Http\Controllers\Api\v1\Transactions\Participants\StatisticsController::class)
        ->name('transactions.participants_statistics');

    Route::post('medias/edit-image', \App\Http\Controllers\Api\v1\Media\EditImageController::class);
    Route::post('medias/upload-image', \App\Http\Controllers\Api\v1\Media\UploadImageController::class);
});
