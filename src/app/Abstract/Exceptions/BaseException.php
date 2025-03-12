<?php

namespace App\Abstract\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseException extends Exception
{
    public function __construct(
        string $message = "Something went wrong.",
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    abstract public function report(): void;
}
