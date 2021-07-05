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

//Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcome');

Auth::routes();

//Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::group(['middleware' => 'auth.aboveauthor','prefix'=>'admin'], function () {

    Route::get('/','HomeController@index')->name('home');

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
    Route::resource('section', 'SectionController', ['except' => ['show','index','edit','create']]);
    Route::resource('ticket', 'TicketController', ['except' => ['show']]);
    Route::resource('summary', 'SummaryController', ['except' => ['show','index','edit','create']]);
    Route::resource('benefit', 'BenefitController', ['except' => ['show','index','edit','create']]);
    Route::resource('venue', 'VenueController', ['except' => ['show']]);
    Route::resource('partner', 'PartnerController', ['except' => ['show']]);
    Route::resource('exams', 'ExamController', ['except' => ['show']]);
    Route::resource('delivery', 'DeliveryController', ['except' => ['show']]);
    Route::resource('menu', 'MenuController', ['except' => ['show']]);

    Route::post('/summary/update/{summary}', 'SummaryController@update')->name('summary.update');

    Route::get('Transaction/participants', ['as' => 'transaction.participants', 'uses' => 'TransactionController@participants']);
    Route::post('transaction/updateExpirationDate', ['as' => 'transaction.updateExpirationDate', 'uses' => 'TransactionController@updateExpirationDate']);

    //Menu item
    Route::get('menu/add_item', ['as' => 'menu.add_item', 'uses' => 'MenuController@add_item']);
    Route::post('menu/fetchAllMenu', ['as' => 'menu.fetchAllMenu', 'uses' => 'MenuController@fetchAllMenu']);
    Route::post('menu/store_item', ['as' => 'menu.store_item', 'uses' => 'MenuController@store_item']);
    Route::post('menu/remove_item', ['as' => 'menu.remove_item', 'uses' => 'MenuController@remove_item']);

    //Faqs
    Route::get('faqs/categories', ['as' => 'faqs.categories', 'uses' => 'FaqController@index_categories']);
    Route::get('faqs/create_category', ['as' => 'faqs.create_category', 'uses' => 'FaqController@create_category']);
    Route::post('faqs/store_category', ['as' => 'faqs.store_category', 'uses' => 'FaqController@store_category']);
    Route::get('faqs/edit_category/{category}', ['as' => 'faqs.edit_category', 'uses' => 'FaqController@edit_category']);
    Route::put('faqs/update_category/{category}', ['as' => 'faqs.update_category', 'uses' => 'FaqController@update_category']);

    //Dropbox
    Route::get('dropbox/update', ['as' => 'dropbox.update', 'uses' => 'DropboxController@update']);

    //Partner
    Route::get('partner/index_main', ['as' => 'partner.index_main', 'uses' => 'PartnerController@index_main']);
    Route::post('partner/remove_event', ['as' => 'partner.remove_event', 'uses' => 'PartnerController@remove_event']);
    Route::post('partner/fetchAllPartners', ['as' => 'partner.fetchAllPartners', 'uses' => 'PartnerController@fetchAllPartners']);

    //Venue
    Route::get('venue/index_main', ['as' => 'venue.index_main', 'uses' => 'VenueController@index_main']);
    Route::get('venue/create_main', ['as' => 'venue.create_main', 'uses' => 'VenueController@create_main']);
    Route::post('venue/store_main', ['as' => 'venue.store_main', 'uses' => 'VenueController@store_main']);
    Route::get('venue/edit_main/{venue}', ['as' => 'venue.edit_main', 'uses' => 'VenueController@edit_main']);
    Route::post('venue/fetchAllVenues', ['as' => 'venue.fetchAllVenues', 'uses' => 'VenueController@fetchAllVenues']);
    Route::post('venue/remove_event', ['as' => 'venue.remove_event', 'uses' => 'VenueController@remove_event']);

    //City
    Route::get('city/index_main', ['as' => 'city.index_main', 'uses' => 'CityController@index_main']);
    Route::get('city/create_main', ['as' => 'city.create_main', 'uses' => 'CityController@create_main']);
    Route::get('city/edit_main/{city}', ['as' => 'city.edit_main', 'uses' => 'CityController@edit_main']);
    Route::put('city/update_main/{city}', ['as' => 'city.update_main', 'uses' => 'CityController@update_main']);
    Route::post('city/store_main', ['as' => 'city.store_main', 'uses' => 'CityController@store_main']);
    Route::get('city/fetchAllCities', ['as' => 'city.fetchAllCities', 'uses' => 'CityController@fetchAllCities']);


    //Faqs
    Route::post('faqs/fetchAllFaqs', ['as' => 'faqs.fetchAllFaqs', 'uses' => 'FaqController@fetchAllFaqs']);
    Route::post('faqs/store_event', ['as' => 'faqs.store_event', 'uses' => 'FaqController@store_event']);
    Route::get('faqs/assign-event/{event}/{faq}', 'FaqController@assignFaq');
    Route::get('faqs/unsign-event/{event}/{faq}', 'FaqController@unsignFaq');

    //User
    Route::post('user/assignEventToUserCreate', ['as' => 'user.assignToCourse', 'uses' => 'UserController@assignEventToUserCreate']);
    Route::get('user/edit_ticket', ['as' => 'user.edit_ticket', 'uses' => 'UserController@edit_ticket']);
    Route::post('user/remove_ticket_user', ['as' => 'user.remove_ticket_user', 'uses' => 'UserController@remove_ticket_user']);
    Route::get('user/store_ticket', ['as' => 'user.store_ticket', 'uses' => 'UserController@store_ticket']);


    //Custom Ticket
    //ticket.edit
    Route::get('ticket/edit', ['as' => 'ticket.edit', 'uses' => 'TicketController@edit']);
    Route::get('ticket/create_main', ['as' => 'ticket.create_main', 'uses' => 'TicketController@create_main']);
    Route::get('ticket/edit_main/{ticket}', ['as' => 'ticket.edit_main', 'uses' => 'TicketController@edit_main']);
    Route::put('ticket/update_main/{ticket}', ['as' => 'ticket.update_main', 'uses' => 'TicketController@update_main']);
    Route::post('ticket/store_main', ['as' => 'ticket.store_main', 'uses' => 'TicketController@store_main']);
    Route::post('ticket/remove_event', ['as' => 'ticket.remove_event', 'uses' => 'TicketController@remove_event']);
    Route::post('ticket/fetchTicketsById', ['as' => 'ticket.fetchTicketsById', 'uses' => 'TicketController@fetchTicketsById']);
    Route::post('ticket/fetchAllTickets', ['as' => 'ticket.fetchAllTickets', 'uses' => 'TicketController@fetchAllTickets']);


    //custom Topic (inside event)
    Route::get('topics/index_event', ['as' => 'topics.index', 'uses' => 'TopicController@index']);
    Route::get('topics/edit_event', ['as' => 'topics.edit_event', 'uses' => 'TopicController@edit_event']);
    Route::get('topics/create_event', ['as' => 'topics.create_event', 'uses' => 'TopicController@create_event']);
    Route::post('topics/store_event', ['as' => 'topics.store_event', 'uses' => 'TopicController@store_event']);

    //custom Partner (inside event)
    Route::get('partner/index_event', ['as' => 'partner.index', 'uses' => 'PartnerController@index']);
    Route::get('partner/edit_event', ['as' => 'partner.edit_event', 'uses' => 'PartnerController@edit_event']);
    Route::get('partner/create_event', ['as' => 'partner.create_event', 'uses' => 'PartnerController@create_event']);
    Route::post('partner/store_event', ['as' => 'partner.store_event', 'uses' => 'PartnerController@store_event']);

    //Custom unssign user from event

    Route::post('user/store_event', ['as' => 'topics.store_event', 'uses' => 'TopicController@store_event']);

    // Route::get('role/delete/{id}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);

    //Global Settings
    Route::get('global', ['as' => 'global.index', 'uses' => 'GlobalController@index']);
    Route::get('main', ['as' => 'global.course_index', 'uses' => 'GlobalController@course_index']);

    //Profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::put('profile/update_billing', ['as' => 'profile.update_billing', 'uses' => 'ProfileController@update_billing']);

    //Edit Instructor
    Route::post ('lesson/edit_instructor', ['as' => 'lesson.edit_instructor', 'uses' => 'LessonController@edit_instructor']);
    Route::post ('lesson/save_instructor', ['as' => 'lesson.save_instructor', 'uses' => 'LessonController@save_instructor']);
    Route::post ('lesson/remove_lesson', ['as' => 'lesson.remove_lesson', 'uses' => 'LessonController@remove_lesson']);

    //Route::post('lesson/destroy_from_topic1', ['as' => 'lesson.destroy_from_topic1', 'uses' => 'LessonController@destroy_from_topic1']);

    //Media
    Route::put('media/upload-image/{media}', 'MediaController@uploadVersionImage')->name('upload.versionImage');
    Route::post('media/crop_image', ['as' => 'media.crop_image', 'uses' => 'MediaController@crop_image']);
    Route::post('media/crop_profile_image', ['as' => 'media.crop_profile_image', 'uses' => 'MediaController@crop_profile_image']);

    //Route::get('media/createFolder', ['as' => 'media.createFolder', 'uses' => 'MediaController@createFolder']);

    //Events
    Route::put('profile/updateRole', ['as' => 'profile.updateRole', 'uses' => 'ProfileController@updateRole']);

    //Notification
    Route::get('notification', ['as' => 'notification.show', 'uses' => 'NotificationController@index']);

    //Event_view
    //Route::get('events/{id}', ['as' => 'events.assign', 'uses' => 'EventController@assign']);
    Route::post('events/assign_store', ['as' => 'events.assign_store', 'uses' => 'EventController@assign_store']);

    //Event assign ticket
    Route::get('events/ticket/{id}', ['as' => 'events.ticket', 'uses' => 'EventController@assign_ticket']);
    Route::post('events/assign_ticket/{id}', ['as' => 'events.assign_ticket_store', 'uses' => 'EventController@assign_ticket_store']);

    //Event assign method
    Route::post('events/assing-method/{event}','EventController@assignPaymentMethod')->name('event.assing-method');

    Route::post('/events/fetchTopics', ['as' => 'events.fetchTopics', 'uses' => 'EventController@fetchTopics']);


    Route::get('/pages/{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);

    /////Slug
    Route::get('/slug/create/{slug}','SlugController@create');
    Route::post('/slug/update/{slug}','SlugController@update');

    /////Metas
    Route::post('/metas/update/{metas}','MetasController@update')->name('metas.update');


    ///DashboardController
    Route::get('/search-user/{search_term}','Dashboard\DashboardController@searchUser');

    ///PaymentMethods
    Route::get('/payment-methods','Dashboard\PaymentMethodsController@index')->name('payments.index');
    Route::get('/payment-methods/create','Dashboard\PaymentMethodsController@create')->name('payments.create');
    Route::post('/payment-methods/store','Dashboard\PaymentMethodsController@store')->name('payments.store');
    Route::get('/payment-methods/edit/{method}','Dashboard\PaymentMethodsController@edit')->name('payments.edit');
    Route::post('/payment-methods/update/{method}','Dashboard\PaymentMethodsController@update')->name('payments.update');

    //media2
    Route::get('media2/index', ['as' => 'media2.index', 'uses' => 'MediaController@index']);

    //Lessons
    Route::post('/sort-lessons/{event}', 'LessonController@orderLesson')->name('sort-lessons');

    //Benefits
    Route::post('/benefits', 'BenefitController@orderBenefits')->name('sort-benefits');

    //Summaries
    Route::post('/summaries/{event}', 'SummaryController@orderSummaries')->name('sort-summaries');

    //Tickets
    Route::post('/tickets/{event}', 'TicketController@sortTickets')->name('sort-tickets');

    //Faqs
    Route::post('/faqs/{event}', 'FaqController@sortFaqs')->name('sort-faqs');

    //Logos
    Route::get('/logos', 'Dashboard\LogosController@index')->name('logos.index');
    Route::get('/logos/create', 'Dashboard\LogosController@create')->name('logos.create');
    Route::post('/logos/store', 'Dashboard\LogosController@store')->name('logos.store');
    Route::get('/logos/edit/{logo}', 'Dashboard\LogosController@edit')->name('logos.edit');
    Route::put('/logos/update/{logo}', 'Dashboard\LogosController@update')->name('logos.update');

    //Exams
    Route::post('/exams/add-question/{exam}', 'ExamController@addQuestion')->name('exam.add_question');
    Route::post('/exams/update-question/{exam}', 'ExamController@updateQuestion')->name('exam.update-question');
    Route::post('/exams/order-question/{exam}', 'ExamController@orderQuestion')->name('exam.order-questions');

    //ExamsResult
    Route::get('/student-summary/{examResult}/{user_id}','Dashboard\ExamResultController@showResult');


});




Route::group(['prefix' => 'cart','middleware' => ['web']], function () {
//Route::group(['prefix' => 'cart', 'middleware' => 'free.event' ], function() {
//    Route::group(['middleware' => 'auth.sms' ], function () {

        Route::get('/', [ 'as' => 'cart', 'uses' => 'Theme\CartController@index' ]);
        Route::post('/', [ 'as' => 'cart.update', 'uses' => 'Theme\CartController@update' ]);
        Route::get('count', [ 'as' => 'cart.count', 'uses' => 'Theme\CartController@count' ]);
        Route::get('destroy', [ 'as' => 'cart.destroy', 'uses' => 'Theme\CartController@destroy' ]);
        Route::get('checkout', [ 'as' => 'cart.checkout', 'uses' => 'Theme\CartController@checkout' ]);
        Route::post('checkDereeId', [ 'as' => 'cart.dereeid', 'uses' => 'Theme\CartController@checkDereeId' ]);
        Route::post('checkCoupon','Theme\CartController@checkCoupon');
        Route::post('/checkKnocrunchId', [ 'as' => 'cart.knowcrunchid', 'uses' => 'Theme\CartController@checkKnowcrunchId' ]);
        Route::post('checkoutcheck', [ 'as' => 'cart.checkout', 'uses' => 'Theme\CartController@checkoutcheck' ]);
        Route::post('/remove', [ 'as' => 'cart.remove-item', 'uses' => 'Theme\CartController@dpremove']);

        //Route::group([ 'prefix' => '{id}' ], function() {
        //    /*Route::post('/dpremove', [ 'as' => 'cart.remove-item', 'uses' => 'Theme\CartController@dpremove']);*/
        //    Route::get('/{ticket}/{type}/add', [ 'as' => 'cart.add-item', 'uses' => 'Theme\CartController@add']);

        //    //Route::get('move', [ 'as' => 'cart.move-item', 'uses' => 'Theme\CartController@move']);

        //});
   // });
//});
});

Route::post('/card/store_from_payment',  'Theme\CardController@store_from_payment');
Route::post('pay-sbt', [
    'as' => 'userPaySbt', 'uses' => 'Theme\CartController@userPaySbt'
]);

Route::group([ 'prefix' => 'info'], function () {
    Route::get('order_error', [
            'as' => 'info.Order.Error', 'uses' => 'Theme\InfoController@orderError'
        ]);
    Route::get('order_success', [
            'as' => 'info.Order.Success', 'uses' => 'Theme\InfoController@orderSuccess'
        ]);
    });

Route::group([ 'prefix' => '{id}' ], function() {
    /*Route::post('/dpremove', [ 'as' => 'cart.remove-item', 'uses' => 'Theme\CartController@dpremove']);*/
    Route::get('/{ticket}/{type}/add', [ 'as' => 'cart.add-item', 'uses' => 'Theme\CartController@add']);

    //Route::get('move', [ 'as' => 'cart.move-item', 'uses' => 'Theme\CartController@move']);

});


Route::group(['middleware' => ['preview','web']], function () {
    Route::get('/', 'Theme\HomeController@homePage');
    Route::post('/add-payment-method', 'Theme\HomeController@addPaymentMethod')->name('add.paymentMethod');
    Route::get('{slug}', 'Theme\HomeController@index');

});


