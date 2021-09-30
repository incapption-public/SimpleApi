<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class StringResult extends MethodResult implements iMethodResult
{
    /**
     * @var string
     */
    private $message;

    public function __construct(HttpStatusCode $statusCode, string $message)
    {
        $this->statusCode = $statusCode;
        $this->message    = $message;
    }

    public function getJson(): string
    {
        return json_encode([
            self::JSON_KEY_STATUS_CODE => $this->getStatusCode()->getValue(),
            self::JSON_KEY_PAYLOAD     => $this->message,
        ]);
    }
}