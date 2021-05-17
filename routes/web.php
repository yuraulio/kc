<?php

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

Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcome');

Auth::routes();

Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('category', 'CategoryController', ['except' => ['show']]);
    Route::resource('tag', 'TagController', ['except' => ['show']]);
    Route::resource('item', 'ItemController', ['except' => ['show']]);
    Route::resource('role', 'RoleController', ['except' => ['show']]);
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::resource('pages', 'PagesController', ['except' => ['show']]);
    Route::resource('instructors', 'InstructorController', ['except' => ['show']]);
    Route::resource('topics', 'TopicController', ['except' => ['show']]);
    Route::resource('lessons', 'LessonController', ['except' => ['show']]);
    Route::resource('events', 'EventController', ['except' => ['show']]);
    Route::resource('types', 'TypeController', ['except' => ['show']]);
    Route::resource('testimonials', 'TestimonialController', ['except' => ['show']]);
    Route::resource('faqs', 'FaqController', ['except' => ['show']]);
    Route::resource('career', 'CareerController', ['except' => ['show']]);
    Route::resource('city', 'CityController', ['except' => ['show']]);
    Route::resource('section', 'SectionController', ['except' => ['show']]);

    // Route::get('role/delete/{id}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);

    //Global Settings
    Route::get('global', ['as' => 'global.index', 'uses' => 'GlobalController@index']);

    //Profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::put('profile/updateRole', ['as' => 'profile.updateRole', 'uses' => 'ProfileController@updateRole']);

    //Notification
    Route::get('notification', ['as' => 'notification.show', 'uses' => 'NotificationController@index']);

    //Event_view
    Route::get('events/{id}', ['as' => 'events.assign', 'uses' => 'EventController@assign']);
    Route::post('events/assign_store/{id}', ['as' => 'events.assign_store', 'uses' => 'EventController@assign_store']);

    Route::post('/events/fetchTopics', ['as' => 'events.fetchTopics', 'uses' => 'EventController@fetchTopics']);


    Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});


