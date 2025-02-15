<?php

use App\Http\Controllers\Admin_api\AdminController;
use App\Http\Controllers\Admin_api\CategoriesController;
use App\Http\Controllers\Admin_api\ClonePageController;
use App\Http\Controllers\Admin_api\CommentsController;
use App\Http\Controllers\Admin_api\CountdownController;
use App\Http\Controllers\Admin_api\DashboardController;
use App\Http\Controllers\Admin_api\InstagramController;
use App\Http\Controllers\Admin_api\MediaController;
use App\Http\Controllers\Admin_api\PagesController;
use App\Http\Controllers\Admin_api\TemplatesController;
use App\Http\Controllers\Admin_api\TickerController;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.app_domain'))->group(function () {
    Route::group(['middleware' => ['auth:admin_web']], function () {
        Route::prefix('get_widget_data')->group(function () {
            Route::get('users', [DashboardController::class, 'get_widget_data_users']);
            Route::get('admins', [DashboardController::class, 'get_widget_data_admins']);
            Route::get('instructors', [DashboardController::class, 'get_widget_data_instructors']);
            Route::get('students', [DashboardController::class, 'get_widget_data_students']);
            Route::get('students_all', [DashboardController::class, 'get_widget_data_students_all']);
            Route::get('graduates', [DashboardController::class, 'get_widget_data_graduates']);
            Route::get('comments', [DashboardController::class, 'get_widget_comments']);
            Route::get('pages', [DashboardController::class, 'get_widget_pages']);
        });

        // categories
        Route::post('categories/deleteMultiple', [CategoriesController::class, 'deleteMultiple']);
        Route::post('categories/widgets', [CategoriesController::class, 'widgets']);
        Route::resource('categories', Admin_api\CategoriesController::class)->only([
            'index', 'store', 'update', 'show', 'destroy',
        ]);

        // templates
        Route::post('templates/deleteMultiple', [TemplatesController::class, 'deleteMultiple']);
        Route::post('templates/widgets', [TemplatesController::class, 'widgets']);
        Route::resource('templates', Admin_api\TemplatesController::class)->only([
            'index', 'store', 'update', 'show', 'destroy',
        ]);

        // tickers
        Route::post('tickers/deleteMultiple', ['App\Http\Controllers\Admin_api\TickerController', 'deleteMultiple']);
        Route::post('tickers/widgets', [TickerController::class, 'widgets']);
        Route::resource('tickers', Admin_api\TickerController::class)->only([
            'index', 'store', 'update', 'show', 'destroy',
        ]);

        // Countdowns
        Route::post('countdown/deleteMultiple', ['App\Http\Controllers\Admin_api\CountdownController', 'deleteMultiple']);
        Route::post('countdown/widgets', [CountdownController::class, 'widgets']);
        Route::resource('countdown', CountdownController::class)->only([
            'index', 'store', 'update', 'show', 'destroy',
        ]);
        Route::put('countdown/update_published/{id}', ['App\Http\Controllers\Admin_api\CountdownController', 'updatePublished']);

        //Royalties
        Route::resource('royalties', Admin_api\RoyaltiesController::class)->only([
            'index', 'show',
        ]);

        Route::get('royalties-settings', ['App\Http\Controllers\Admin_api\RoyaltiesController', 'getRoyaltiesSettings']);
        Route::post('royalties/export', ['App\Http\Controllers\Admin_api\RoyaltiesController', 'exportInstructorList']);
        Route::post('royalties/{id}/export', ['App\Http\Controllers\Admin_api\RoyaltiesController', 'export']);
        Route::post('royalties/widgets', ['App\Http\Controllers\Admin_api\RoyaltiesController', 'widgets']);

        // Categories Event
        Route::get('getCategories', ['App\Http\Controllers\Admin_api\CategoryEventController', 'getList']);

        // Delivery
        Route::get('getDeliveries', ['App\Http\Controllers\Admin_api\DeliveryController', 'getList']);
        // Event
        Route::get('getEventsList', ['App\Http\Controllers\Admin_api\EventController', 'getList']);
        // Event
        Route::get('getAllEventsList', ['App\Http\Controllers\Admin_api\EventController', 'getAllList']);

        // pages
        Route::post('pages/deleteMultiple', [PagesController::class, 'deleteMultiple']);
        Route::post('pages/priorities', [PagesController::class, 'priorities']);
        Route::post('pages/widgets', [PagesController::class, 'widgets']);
        Route::resource('pages', Admin_api\PagesController::class, ['as' => 'admin'])->only([
            'index', 'store', 'update', 'show', 'destroy',
        ]);
        Route::post('pages/{page}/clone', ClonePageController::class);

        // comments
        Route::post('comments/deleteMultiple', [CommentsController::class, 'deleteMultiple']);
        Route::post('comments/widgets', [CommentsController::class, 'widgets']);
        Route::resource('comments', Admin_api\CommentsController::class)->only([
            'index', 'destroy',
        ]);

        // settings
        Route::resource('settings', Admin_api\SettingsController::class)->only([
            'index', 'update',
        ]);

        // users
        Route::prefix('users')->group(function () {
            Route::post('admins/deleteMultiple', [AdminController::class, 'deleteMultiple']);
            Route::resource('admins', Admin_api\AdminController::class)->only([
                'index', 'store', 'update', 'show', 'destroy',
            ]);
        });

        Route::put('pages/update_published/{id}', [PagesController::class, 'updatePublished']);
        Route::put('ticker/update_published/{id}', [TickerController::class, 'updatePublished']);

        Route::post('pages/upload_image', [PagesController::class, 'uploadImage']);
        Route::resource('media_manager', Admin_api\MediaController::class, ['as' => 'admin'])->only([
            'index', 'store', 'update', 'destroy',
        ]);

        Route::post('media_manager/upload_reg_file', [MediaController::class, 'uploadRegFile']);
        Route::get('media_manager/files', [MediaController::class, 'files']);
        Route::get('media_manager/getFile/{id}', [MediaController::class, 'getFile']);
        Route::delete('media_manager/file/{id}', [MediaController::class, 'deleteFile']);
        Route::post('media_manager/deleteFiles', [MediaController::class, 'deleteFiles']);
        Route::delete('media_manager/folder/{id}', [MediaController::class, 'deleteFolder']);
        Route::post('media_manager/folder/edit', [MediaController::class, 'editFolder']);
        Route::post('media_manager/file/move', [MediaController::class, 'moveFile']);
        Route::post('media_manager/files/move', [MediaController::class, 'moveFiles']);
        Route::post('media_manager/change_folder_order', [MediaController::class, 'changeFolderOrder']);

        Route::get('getMenus', '\CodexShaper\Menu\Http\Controllers\MenuController@getMenus');
        Route::get('getDisplayOptions', [PagesController::class, 'getDisplayOptions']);
        Route::get('getGaleryDisplayOptions', [PagesController::class, 'getGaleryDisplayOptions']);
        Route::get('getFormTypes', [PagesController::class, 'getFormTypes']);
        Route::get('getEventTypes', [PagesController::class, 'getEventTypes']);
        Route::get('templatesAll', [TemplatesController::class, 'templatesAll']);
        Route::get('getHomepageGalleryOptions', [PagesController::class, 'getHomepageGalleryOptions']);
        Route::get('getListSource', [PagesController::class, 'getListSource']);
        Route::get('getSearchSource', [PagesController::class, 'getSearchSource']);
        Route::get('getPageTypes', [PagesController::class, 'getPageTypes']);

        Route::get('category_group/{id}', [CategoriesController::class, 'getCategoriesForPageType']);

        Route::get('getEvents', [PagesController::class, 'getEvents']);
        Route::get('getPlans', [PagesController::class, 'getPlans']);

        Route::get('getProfile/{profile}', [InstagramController::class, 'getProfile']);
    });
});

// for use in the old admin
Route::group(['middleware' => ['auth.aboveauthor']], function () {
    Route::resource('media_manager', Admin_api\MediaController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    Route::post('media_manager/upload_reg_file', [MediaController::class, 'uploadRegFile']);
    Route::get('media_manager/files', [MediaController::class, 'files']);
    Route::get('media_manager/getFile/{id}', [MediaController::class, 'getFile']);
    Route::delete('media_manager/file/{id}', [MediaController::class, 'deleteFile']);
    Route::delete('media_manager/folder/{id}', [MediaController::class, 'deleteFolder']);
    Route::post('media_manager/folder/edit', [MediaController::class, 'editFolder']);
    Route::post('media_manager/file/move', [MediaController::class, 'moveFile']);
});
