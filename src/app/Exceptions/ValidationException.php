<?php

namespace App\Exceptions;

use App\Abstract\Exceptions\BaseException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends BaseException
{
    public function __construct(
        mixed $message = "Validation Exception.",
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function report(): void {}
}
