<?php

use App\Http\Controllers\Admin_api\MediaController;
use App\Http\Controllers\Admin_api\PagesController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\CityAutocompleteController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\v1\FacebookAuthController;
use App\Http\Controllers\Api\v1\CareerController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CityController;
use App\Http\Controllers\Api\v1\CountryController;
use App\Http\Controllers\Api\v1\DashboardController;
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
use App\Http\Controllers\Api\v1\Event\Participants\EventActiveStudentsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventDownloadExamResultsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventDownloadStudentsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventDownloadSyllabusController;
use App\Http\Controllers\Api\v1\Event\Participants\EventExamResultsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventRevenueStatsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventReviewsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventSaleStatsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventSettingsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventStatsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventTicketStatsController;
use App\Http\Controllers\Api\v1\Event\Participants\EventTotalHoursController;
use App\Http\Controllers\Api\v1\Exam\ExamCategoryController;
use App\Http\Controllers\Api\v1\Exam\ExamController;
use App\Http\Controllers\Api\v1\Exam\ExamResultController;
use App\Http\Controllers\Api\v1\InstructorController;
use App\Http\Controllers\Api\v1\Media\EditImageController;
use App\Http\Controllers\Api\v1\Media\UploadImageController;
use App\Http\Controllers\Api\v1\Messaging\EmailController;
use App\Http\Controllers\Api\v1\Messaging\MessageCategoryController;
use App\Http\Controllers\Api\v1\Messaging\MobileNotificationController;
use App\Http\Controllers\Api\v1\Messaging\WebNotificationController;
use App\Http\Controllers\Api\v1\MessagingActivityController;
use App\Http\Controllers\Api\v1\PartnerController;
use App\Http\Controllers\Api\v1\PaymentMethodController;
use App\Http\Controllers\Api\v1\Report\ReportController;
use App\Http\Controllers\Api\v1\ReviewController;
use App\Http\Controllers\Api\v1\SkillController;
use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\TopicController;
use App\Http\Controllers\Api\v1\Transactions\Participants\StatisticsController;
use App\Http\Controllers\Api\v1\TypeController;
use App\Http\Controllers\Api\v1\UserController as V1UserControllerAlias;
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

Route::get('auth/facebook', [FacebookAuthController::class, 'redirect']);
Route::get('auth/facebook/callback', [FacebookAuthController::class, 'callback']);

Route::get('/run-artisan/{command}', [EmailController::class, 'runCommand']);

Route::middleware('auth:api')->group(function () {
    // User
    Route::get('myprofile', [UserController::class, 'profile']);
    Route::post('myprofile/update', [UserController::class, 'updateProfile']);
    Route::get('myprofile/events', [UserController::class, 'events']);
    Route::get('impersonate/{user}', [UserController::class, 'impersonate']);
    Route::post('users/{user}/update-status', [UserController::class, 'updateStatus']);
    Route::post('users/batch', [UserController::class, 'batchDestroy']);
    Route::post('users/import', [UserController::class, 'import']);
    Route::get('users/export', [UserController::class, 'export']);
    Route::apiResource('users', UserController::class);

    // Coupons
    Route::apiResource('coupons', CouponController::class)
        ->only(['index']);

    // Plans
    Route::apiResource('plans', PlanController::class)
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

    Route::post('download/users-sample', [MediaController::class, 'downloadExportExample']);

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

    Route::get('cities', CityAutocompleteController::class);

    // Notifications
    Route::resource('notifications', NotificationController::class)
        ->only(['index', 'update']);

    Route::prefix('roles')->group(function () {
        Route::get('', [RoleController::class, 'index']);
        Route::get('{role}', [RoleController::class, 'show']);
        Route::post('', [RoleController::class, 'store']);
        Route::put('{role}', [RoleController::class, 'update']);
        Route::delete('{role}', [RoleController::class, 'destroy']);
    });

    Route::prefix('tags')->group(function () {
        Route::get('', [TagController::class, 'index']);
        Route::get('{tag}', [TagController::class, 'show']);
        Route::post('', [TagController::class, 'store']);
        Route::delete('{tag}', [TagController::class, 'destroy']);
    });

    Route::prefix('skills')->group(function () {
        Route::get('', [SkillController::class, 'index']);
        Route::get('{skill}', [SkillController::class, 'show']);
        Route::post('', [SkillController::class, 'store']);
        Route::put('{skill}', [SkillController::class, 'update']);
        Route::delete('{skill}', [SkillController::class, 'destroy']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('', [ReviewController::class, 'index']);
        Route::get('{review}', [ReviewController::class, 'show']);
        Route::post('', [ReviewController::class, 'store']);
        Route::put('{review}', [ReviewController::class, 'update']);
        Route::delete('{review}', [ReviewController::class, 'destroy']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('statistic', [DashboardController::class, 'statistic']);
    });

    Route::prefix('v1')->group(function () {
        Route::prefix('users')->group(function () {
            Route::post('delete-batch', [UserController::class, 'batchDestroy']);

            Route::post('login-as/{user}', [V1UserControllerAlias::class, 'loginAs']);
            Route::post('import', [V1UserControllerAlias::class, 'importUsers']);
            Route::get('payments/{user}', [V1UserControllerAlias::class, 'getPayments']);

            Route::get('', [V1UserControllerAlias::class, 'index']);
            Route::get('{user}', [V1UserControllerAlias::class, 'show']);
            Route::post('', [V1UserControllerAlias::class, 'store']);
            Route::put('{user}', [V1UserControllerAlias::class, 'update']);
            Route::delete('{user}', [V1UserControllerAlias::class, 'destroy']);

            Route::prefix('counts')->group(function () {
                Route::get('admins', [V1UserControllerAlias::class, 'adminsCounts']);
                Route::get('students', [V1UserControllerAlias::class, 'studentsCounts']);
                Route::get('instructors', [V1UserControllerAlias::class, 'instructorsCounts']);
            });
        });
        Route::prefix('events')->group(function () {
            Route::post('{event}/clone', [EventController::class, 'duplicateEvent'])->name('api.event.clone');
        });
    });
});

Route::post('/myaccount/reset', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/webhook/brevo', [MessagingActivityController::class, 'store']);

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
    Route::apiResource('countries', CountryController::class)
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
    Route::prefix('topics')->group(function () {
        Route::get('/', [TopicController::class, 'index']);
        Route::get('/{topic}', [TopicController::class, 'show']);
        Route::post('/', [TopicController::class, 'store']);
        Route::post('/{topic}/clone', [TopicController::class, 'clone']);
        Route::put('/{topic}', [TopicController::class, 'update']);
        Route::get('/with-event', [TopicController::class, 'topicWithEvent']);
        Route::delete('/{topic}', [TopicController::class, 'destroy']);
    });

    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'index']);
        Route::get('/{lesson}', [LessonController::class, 'show']);
        Route::post('/', [LessonController::class, 'store']);
        Route::put('/{lesson}', [LessonController::class, 'update']);
        Route::delete('/{lesson}', [LessonController::class, 'destroy']);
    });

    // Events
    Route::apiResource('events', EventController::class);
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
    Route::get('events/{event}/general-stats', EventStatsController::class);
    Route::get('events/{event}/check-slug', [EventController::class, 'checkSlugAvailability']);
    Route::get('events/{event}/participants/total-hours', EventTotalHoursController::class);
    Route::get('events/{event}/participants/exam-results', EventExamResultsController::class);
    Route::get('events/{event}/participants/active-students', EventActiveStudentsController::class);
    Route::get('events/{event}/participants/ticket-stats', EventTicketStatsController::class);
    Route::get('events/{event}/participants/sale-stats', EventSaleStatsController::class);
    Route::get('events/{event}/participants/revenue-stats', EventRevenueStatsController::class);
    Route::get('events/{event}/participants/reviews', EventReviewsController::class);
    Route::get('events/{event}/participants/download-students', EventDownloadStudentsController::class);
    Route::get('events/{event}/participants/download-exam-results', EventDownloadExamResultsController::class);
    Route::get('events/{event}/participants/download-syllabus', EventDownloadSyllabusController::class);
    Route::get('events/{event}/participants/settings', [EventSettingsController::class, 'getSettings']);
    Route::put('events/{event}/participants/settings', [EventSettingsController::class, 'updateSettings']);

    // Exams
    Route::apiResource('exams', ExamController::class);
    Route::post('exams/{exam}/duplicate', [ExamController::class, 'duplicateExam'])->name('exam.clone');
    Route::post('exams/{exam}/update-questions', [ExamController::class, 'updateQuestions'])->name('exam.update-questions');
    Route::get('exams/{exam}/live-results', [ExamResultController::class, 'getLiveResults']);
    Route::apiResource('exam-categories', ExamCategoryController::class);

    // Messaging

    // Email
    Route::apiResource('messaging/categories', MessageCategoryController::class);
    Route::apiResource('messaging/emails', EmailController::class);
    Route::get('messaging/emails-templates', [EmailController::class, 'getTemplates']);
    Route::get('messaging/emails-triggers', [EmailController::class, 'getEmailTriggers']);
    // Mobile Notification
    Route::apiResource('messaging/mobile-notifications', MobileNotificationController::class);
    // Web Notification
    Route::apiResource('messaging/web-notifications', WebNotificationController::class);

    // Messaging Activity
    Route::get('messaging-activity/by-user/{id}', [MessagingActivityController::class, 'showByUser']);
    Route::get('messaging-activity/by-event/{id}', [MessagingActivityController::class, 'showByEvent']);
    Route::delete('messaging-activity/{id}', [MessagingActivityController::class, 'destroy']);
    Route::post('messaging-activity/send-again', [MessagingActivityController::class, 'sendAgain']);
    Route::get('messaging-activity/email-criteria/{id}', [MessagingActivityController::class, 'getEmail']);

    //Reports
    Route::apiResource('reports', ReportController::class);
    Route::post('reports/{report}/export', [ReportController::class, 'exportReportResults']);
    Route::post('reports/{report}/calculate', [ReportController::class, 'fetchReportResults']);

    Route::apiResource('exams.results', ExamResultController::class)
        ->only(['index']);

    // Instructor
    Route::apiResource('instructors', InstructorController::class)
        ->only(['index']);

    // Career
    Route::apiResource('careers', CareerController::class)
        ->only(['index']);
});

Route::domain(config('app.app_domain'))->group(function () {
    Route::group(['middleware' => ['auth:admin_api_api'], 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
        Route::post('medias/upload-image', UploadImageController::class);
        Route::post('medias/edit-image', EditImageController::class);
    });
});
