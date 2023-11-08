<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iWebhookController
{
    public function __construct(ApiRequest $request);

    public function handle(ApiRequest $request): iMethodResult;
}