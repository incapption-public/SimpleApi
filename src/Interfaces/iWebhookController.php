<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iWebhookController
{
    public function handle(ApiRequest $request): iMethodResult;
}