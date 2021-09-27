<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Enums\HttpStatusCode;

interface iMethodResult {
    public function getStatusCode() : HttpStatusCode;
    public function getJson() : string;
}