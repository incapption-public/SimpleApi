<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;

abstract class MethodResult
{
    /**
     * @var HttpStatusCode
     */
    protected $statusCode;
    protected const JSON_KEY_MESSAGE = 'message';
    protected const JSON_KEY_ERRORS = 'errors';

    public function getStatusCode(): HttpStatusCode
    {
        return $this->statusCode;
    }

    abstract function getJson(): string;
}