<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class EmptyResult extends MethodResult implements iMethodResult
{
    public function __construct(HttpStatusCode $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getJson(): string
    {
        return '';
    }
}