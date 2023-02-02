<?php

return [
    /*
     * The client_id from registering your app on Instagram
     */
    'client_id'           => '1244054176197707',

    /*
     * The client secret from registering your app on Instagram,
     * This is not the same as an access token.
     */
    'client_secret'       => '93fec8dcd36f8cd424134b32fe04c34c',

    /*
     * The base url used to generate to auth callback route for instagram.
     * This defaults to your APP_URL, so normally you may leave it as null
     */
    //'base_url' => null,
    'base_url' => 'https://admin.kcversion8.test',
    /*
     * The route that will respond to the Instagram callback during the OAuth process.
     * Only enter the path without the leading slash. You need to ensure that you have registered
     * a redirect_uri for your instagram app that is equal to combining the
     *  app url (from config) and this route
     */
    'auth_callback_route' => 'instagram-auth-response',

    /*
     * On success of the OAuth process you will be redirected to this route.
     * You may use query strings to carry messages
     */
    'success_redirect_to' => 'instagram-auth-response?result=success',

    /*
     * If the OAuth process fails for some reason you will be redirected to this route.
     * You may use query strings to carry messages
     */
    'failure_redirect_to' => 'instagram-auth-response?result=failure',

    /*
     * You may filter out video media types by setting this to true. Carousel media
     * will become the first image in the carousel, and if there are no images, then
     * the entire carousel will be ignored.
     */
    'ignore_video' => false,

    /*
     * You may set an email address below if you wish to be notified of errors when
     * attempting to refresh the Instagram feed.
     */
    'notify_on_error' => null,
];
