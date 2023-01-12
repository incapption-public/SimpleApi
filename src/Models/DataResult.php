<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class DataResult extends MethodResult implements iMethodResult
{
    /**
     * @var array
     */
    private $data;

    public function __construct(HttpStatusCode $statusCode, array $data)
    {
        $this->statusCode = $statusCode;
        $this->data       = $data;
    }

    public function getJson(): string
    {
        return json_encode($this->data);
    }
}