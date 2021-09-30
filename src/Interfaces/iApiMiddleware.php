<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iApiMiddleware {
    public function handle(ApiRequest $apiRequest);
}