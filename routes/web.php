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

    // Route::get('role/delete/{id}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);

    //Global Settings
    Route::get('global', ['as' => 'global.index', 'uses' => 'GlobalController@index']);

    //Profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    //Notification
    Route::get('notification', ['as' => 'notification.show', 'uses' => 'NotificationController@index']);

    


    Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});


