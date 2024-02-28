<?php

use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\RoleController;
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
    Route::get('myprofile', [UserController::class, 'profile']);
    Route::post('myprofile/update', [UserController::class, 'updateProfile']);
    Route::get('myprofile/events', [UserController::class, 'events']);
    Route::get('impersonate/{user}', [UserController::class, 'impersonate']);
    Route::post('users/{user}/update-status', [UserController::class, 'updateStatus']);
    Route::delete('users/batch', [UserController::class, 'batchDestroy']);
    Route::post('users/import', [UserController::class, 'import']);
    Route::apiResource('users', UserController::class);

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
    Route::post('absences', [AbsenceController::class, 'store']);

    //GetDropboxToken
    Route::get('get-dropbox-token', [UserController::class, 'getDropBoxToken']);

    // Events
    Route::resource('events', EventController::class)
        ->only(['index', 'show']);

    // Notifications
    Route::resource('notifications', NotificationController::class)
        ->only(['index', 'update']);

    // Roles
    Route::resource('roles', RoleController::class)
        ->only('index');
});

Route::post('/myaccount/reset', [ForgotPasswordController::class, 'sendResetLinkEmail']);
