<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iConstructableApiController
{
    public function __construct(ApiRequest $request);
}