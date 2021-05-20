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
    Route::resource('ticket', 'TicketController', ['except' => ['show']]);
    Route::resource('summary', 'SummaryController', ['except' => ['show']]);
    Route::resource('benefit', 'BenefitController', ['except' => ['show']]);
    Route::resource('venue', 'VenueController', ['except' => ['show']]);




    //User
    Route::post('user/assignEventToUserCreate', ['as' => 'user.assignToCourse', 'uses' => 'UserController@assignEventToUserCreate']);

    //Custom Ticket
    //ticket.edit
    Route::get('ticket/edit', ['as' => 'ticket.edit', 'uses' => 'TicketController@edit']);
    Route::get('ticket/create_main', ['as' => 'ticket.create_main', 'uses' => 'TicketController@create_main']);
    Route::get('ticket/edit_main/{ticket}', ['as' => 'ticket.edit_main', 'uses' => 'TicketController@edit_main']);
    Route::put('ticket/update_main/{ticket}', ['as' => 'ticket.update_main', 'uses' => 'TicketController@update_main']);
    Route::post('ticket/store_main', ['as' => 'ticket.store_main', 'uses' => 'TicketController@store_main']);

    //custom Topic (inside event)
    Route::get('topics/index_event', ['as' => 'topics.index', 'uses' => 'TopicController@index']);
    Route::get('topics/edit_event', ['as' => 'topics.edit_event', 'uses' => 'TopicController@edit_event']);
    Route::get('topics/create_event', ['as' => 'topics.create_event', 'uses' => 'TopicController@create_event']);
    Route::post('topics/store_event', ['as' => 'topics.store_event', 'uses' => 'TopicController@store_event']);


    // Route::get('role/delete/{id}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);

    //Global Settings
    Route::get('global', ['as' => 'global.index', 'uses' => 'GlobalController@index']);
    Route::get('main', ['as' => 'global.course_index', 'uses' => 'GlobalController@course_index']);

    //Profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);



    //Events
    Route::put('profile/updateRole', ['as' => 'profile.updateRole', 'uses' => 'ProfileController@updateRole']);

    //Notification
    Route::get('notification', ['as' => 'notification.show', 'uses' => 'NotificationController@index']);

    //Event_view
    Route::get('events/{id}', ['as' => 'events.assign', 'uses' => 'EventController@assign']);
    Route::post('events/assign_store', ['as' => 'events.assign_store', 'uses' => 'EventController@assign_store']);

    //Event assign ticket
    Route::get('events/ticket/{id}', ['as' => 'events.ticket', 'uses' => 'EventController@assign_ticket']);
    Route::post('events/assign_ticket/{id}', ['as' => 'events.assign_ticket_store', 'uses' => 'EventController@assign_ticket_store']);


    Route::post('/events/fetchTopics', ['as' => 'events.fetchTopics', 'uses' => 'EventController@fetchTopics']);


    Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});


