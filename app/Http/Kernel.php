<?php

namespace App\Http;

use App\Http\Middleware\BasicAuth;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\BasicAuth::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\FrameGuard::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],

        'admin_api' => [
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'horizon.auth.basic' => \App\Http\Middleware\HorizonBasicAuthMiddleware::class,

        'preview' => \App\Http\Middleware\Preview::class,
        'static_page' => \App\Http\Middleware\StaticPages::class,
        'auth.aboveauthor' => \App\Http\Middleware\AuthAuthorsAndAbove::class,
        'cart' => \App\Http\Middleware\TicketCheck::class,
        'free.event' => \App\Http\Middleware\CheckForFreeEvent::class,
        'code.event' => \App\Http\Middleware\CheckCodeEvent::class,
        'auth.elearning' => \App\Http\Middleware\CheckUserLogeinForElearning::class,
        'auth.sms' => \App\Http\Middleware\CheckForSMSCoockie::class,
        'auth.sms.api' => \App\Http\Middleware\CheckSmsForApi::class,
        'event.check' => \App\Http\Middleware\CheckForEvent::class,
        'event.subscription' => \App\Http\Middleware\CheckForSubscription::class,
        'cert.owner' => \App\Http\Middleware\CertificateOwner::class,
        'exam.access' => \App\Http\Middleware\ExamCheck::class,

        'registration.check' => \App\Http\Middleware\Registration::class,
        'billing.check' => \App\Http\Middleware\Billing::class,
        'instructor-terms' => \App\Http\Middleware\CheckInstructorTermsPage::class,

        'logout.devices' => \App\Http\Middleware\ClearOldSessionsMiddleware::class,
        'frameHeaders' => \App\Http\Middleware\FrameHeadersMiddleware::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
}
