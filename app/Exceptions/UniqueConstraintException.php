<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class UniqueConstraintException extends Exception
{
    /**
     * Create a new UniqueConstraintException.
     *
     * @param  string  $message  The exception message.
     * @param  int  $code  The HTTP status code (default: 409 Conflict).
     * @param  Exception|null  $previous  The previous exception.
     */
    public function __construct(string $message = 'Unique constraint violation', int $code = 409, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
