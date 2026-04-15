<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedActionException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'Unauthorized action', int $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 403;
    }
}
