<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class ErrorResult extends MethodResult implements iMethodResult
{
    /**
     * @var array|string
     */
    private $errors;

    /**
     * ErrorResult constructor.
     *
     * @param HttpStatusCode $statusCode
     * @param array|string   $errors
     */
    public function __construct(HttpStatusCode $statusCode, $errors)
    {
        $this->statusCode = $statusCode;
        $this->errors       = $errors;
    }

    public function getJson(): string
    {
        return json_encode([
            self::JSON_KEY_ERRORS => $this->errors,
        ]);
    }
}