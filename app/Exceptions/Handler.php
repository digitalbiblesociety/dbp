<?php

namespace App\Exceptions;

use App\Mail\ExceptionOccured;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Mail;
use ReflectionClass;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        $enableEmailExceptions = config('exceptions.emailExceptionEnabled');

        if ($enableEmailExceptions === '') {
            $enableEmailExceptions = config('exceptions.emailExceptionEnabledDefault');
        }

        if ($enableEmailExceptions && $this->shouldReport($exception)) {
            $this->sendEmail($exception);
        }
        $sentry_dsn = config('sentry.dsn');

        if ($sentry_dsn && config('app.env') == 'production' && $this->shouldReport($exception) && app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (config('app.env') == 'local') {
            return parent::render($request, $exception);
        }

        return $this->handleApiException($request, $exception);
    }

    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
            if (!config('app.env') !== 'debug') {
                return $exception;
            }
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
            $responseCode = $exception->getStatusCode();
        } else {
            $responseCode = $exception->getCode();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = [];
        $response['error'] = Response::$statusTexts[$statusCode];

        $class = new ReflectionClass(new Response());
        $constants = array_flip($class->getConstants());

        $response['type'] = $constants[$statusCode] ?? $constants[Response::HTTP_INTERNAL_SERVER_ERROR];

        if ($statusCode === Response::HTTP_UNPROCESSABLE_ENTITY) {
            $message = $exception->getMessage();
            if ($message === '') {
                $message = Response::$statusTexts[$statusCode];
            }
            if (\is_object($message)) {
                $message = $message->toArray();
            }
            $response['error'] = $message;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
        }
        $response['status_code'] = $responseCode;
        $response['host_name'] = gethostname();
        return response()->json($response, $statusCode);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $exception->api_response) {
            $response = [];
            $response['error'] = Response::$statusTexts[Response::HTTP_UNAUTHORIZED];
            if (config('app.debug')) {
                $response['trace'] = $exception->getTrace();
            }
            $response['status_code'] = Response::HTTP_UNAUTHORIZED;
            $response['host_name'] = gethostname();
            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * Sends an email upon exception.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        try {
            $e = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();
            $html = $handler->getHtml($e);

            Mail::send(new ExceptionOccured($html));
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}
