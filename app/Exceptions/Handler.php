<?php

namespace App\Exceptions;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return "3";
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Auth\AuthenticationException && $request->header('Accept') === 'application/json') {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'response' => NULL
            ], 200);
        }

        if ($exception instanceof ValidationException && $request->header('Accept') === 'application/json') {
            $errors = $exception->validator->errors()->getMessages();
            return new JsonResponse([
                'error' => true,
                'message' => $exception->getMessage(),
                // 'errors' => $errors,
                'response' => NULL
            ], 200);
        }

        return parent::render($request, $exception);
    }
}
