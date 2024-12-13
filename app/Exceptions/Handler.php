<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\PaymentService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Server\Exception\OAuthServerException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
        \Lcobucci\JWT\Validation\RequiredConstraintsViolated::class,
        UserAlreadyEnrolledToTheCourseException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof OAuthServerException && $e->getMessage() == 'The resource owner or authorization server denied the request.') {
                Log::info($e);

                return false;
            }
        });
    }

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response|JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            if ($request->acceptsJson() || $request->wantsJson() || $request->is('api/*')) {
                return new JsonResponse(['message' => $exception->getMessage()], 404);
            }
        }

        if ($exception instanceof UserAlreadyEnrolledToTheCourseException) {
            $message = 'The user(s) with email: ' .
                implode(',', $exception->getEmails()) .
                (count($exception->getEmails()) > 1 ? ' are' : ' is') .
                ' already enrolled' .
                ' to the course(s): ' . implode(',', $exception->getEvents());

            if ($request->ajax()) {
                session()->put('dperror', $message);

                return response()->json([
                    'status' => 'fail',
                    'errors' => [
                        $message,
                    ],
                ], 422);
            } else {
                return redirect()->back()->with(
                    'dperror',
                    $message,
                );
            }
        }

        return parent::render($request, $exception);
    }
}
