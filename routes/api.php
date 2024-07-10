<?php

use App\Http\Controllers\Admin_api\MediaController;
use App\Http\Controllers\Admin_api\PagesController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CityController;
use App\Http\Controllers\Api\v1\DeliveryController;
use App\Http\Controllers\Api\v1\Event\EventController;
use App\Http\Controllers\Api\v1\Event\EventExamController;
use App\Http\Controllers\Api\v1\Event\EventFaqController;
use App\Http\Controllers\Api\v1\Event\EventFileController;
use App\Http\Controllers\Api\v1\Event\EventInstructorController;
use App\Http\Controllers\Api\v1\Event\EventStudentController;
use App\Http\Controllers\Api\v1\Event\EventTicketController;
use App\Http\Controllers\Api\v1\Event\EventTopicController;
use App\Http\Controllers\Api\v1\Event\EventVenueController;
use App\Http\Controllers\Api\v1\Exam\ExamController;
use App\Http\Controllers\Api\v1\Exam\ExamResultController;
use App\Http\Controllers\Api\v1\Media\EditImageController;
use App\Http\Controllers\Api\v1\Media\UploadImageController;
use App\Http\Controllers\Api\v1\PartnerController;
use App\Http\Controllers\Api\v1\PaymentMethodController;
use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\TopicController;
use App\Http\Controllers\Api\v1\Transactions\Participants\StatisticsController;
use App\Http\Controllers\Api\v1\TypeController;
use Illuminate\Support\Facades\Route;

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
    Route::get('users/export', [UserController::class, 'export']);
    Route::apiResource('users', UserController::class);

    // Coupons
    Route::apiResource('coupons', CouponController::class)
        ->only(['index']);

    // Media manager
    Route::resource('media-manager', MediaController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);
    Route::delete('media-manager/folder/{id}', [MediaController::class, 'deleteFolder']);
    Route::post('media-manager/folder/edit', [MediaController::class, 'editFolder']);
    Route::post('media-manager/change_folder_order', [MediaController::class, 'changeFolderOrder']);

    Route::post('media-manager/file/move', [MediaController::class, 'moveFile']);
    Route::post('media-manager/files/move', [MediaController::class, 'moveFiles']);
    Route::delete('media-manager/file/{id}', [MediaController::class, 'deleteFile']);
    Route::post('media-manager/deleteFiles', [MediaController::class, 'deleteFiles']);
    Route::get('media-manager/files', [MediaController::class, 'files']);
    Route::get('media-manager/getFile/{id}', [MediaController::class, 'getFile']);
    Route::get('media-manager/download/{mediaFile}', [MediaController::class, 'download']);
    Route::post('media-manager/upload_reg_file', [MediaController::class, 'uploadRegFile']);

    // Page manager
    Route::resource('pages', PagesController::class, ['as' => 'admin'])->only([
        'index', 'store', 'update', 'show', 'destroy',
    ]);

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

Route::group(['middleware' => ['auth:api', 'auth.aboveauthor'], 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::post('transactions/participants/statistics', StatisticsController::class)
        ->name('transactions.participants_statistics');

    Route::post('medias/edit-image', EditImageController::class);
    Route::post('medias/upload-image', UploadImageController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class)
        ->only(['index']);

    // Cities
    Route::apiResource('cities', CityController::class)
        ->only(['index']);

    // Partners
    Route::apiResource('partners', PartnerController::class)
        ->only(['index']);

    // Payment methods
    Route::apiResource('payment-methods', PaymentMethodController::class)
        ->only(['index']);

    // Types
    Route::apiResource('types', TypeController::class)
        ->only(['index']);

    // Deliveries
    Route::apiResource('deliveries', DeliveryController::class)
        ->only(['index']);

    // Tickets
    Route::apiResource('tickets', TicketController::class)
        ->only(['index']);

    // Topics
    Route::apiResource('topics', TopicController::class)
        ->only(['index']);

    // Nested resources of events.
    Route::apiResource('events.faqs', EventFaqController::class)
        ->only(['index']);
    Route::apiResource('events.topics', EventTopicController::class)
        ->only(['index']);
    Route::apiResource('events.tickets', EventTicketController::class)
        ->only(['index']);
    Route::apiResource('events.instructors', EventInstructorController::class)
        ->only(['index']);
    Route::apiResource('events.students', EventStudentController::class)
        ->only(['index']);
    Route::apiResource('events.venues', EventVenueController::class)
        ->only(['index']);
    Route::apiResource('events.exams', EventExamController::class)
        ->only(['index', 'show']);
    Route::apiResource('events.files', EventFileController::class)
        ->only(['index']);

    // Exams
    Route::apiResource('exams', ExamController::class)
        ->only(['index', 'show']);
    Route::apiResource('exams.results', ExamResultController::class)
        ->only(['index']);
});

Route::domain(config('app.prefix_new_admin') . config('app.app_domain'))->group(function () {
    Route::group(['middleware' => ['auth:admin_api_api'], 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
        Route::post('medias/upload-image', UploadImageController::class);
        Route::post('medias/edit-image', EditImageController::class);
    });
});
