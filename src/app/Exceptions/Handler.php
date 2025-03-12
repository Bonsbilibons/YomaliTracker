<?php

namespace App\Exceptions;

use App\Abstract\Exceptions\BaseException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function report(Throwable $exception): void
    {
        if ($exception instanceof BaseException) {
            $exception->report();
        }

        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        $message = 'Something went wrong.';
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof BaseException) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
        } else if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $message = $exception->validator->getMessageBag()->toArray();
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        return response()->json(
            [
                'status'  => false,
                'message' => $message
            ],
            $code
        );
    }
}
