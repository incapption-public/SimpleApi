<?php

namespace Incapption\SimpleApi\Exceptions;

use Exception;
use Throwable;
use Incapption\SimpleApi\Enums\HttpStatusCode;

class SimpleApiException extends Exception
{
    /**
     * @var HttpStatusCode|null
     */
    private $statusCode;

    public function __construct(string $message, HttpStatusCode $statusCode = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    public function hasStatusCode() : bool
    {
        return isset($this->statusCode);
    }

    public function getStatusCode() : ?HttpStatusCode
    {
        return $this->statusCode;
    }
}