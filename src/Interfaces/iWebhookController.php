<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iWebhookController
{
    /**
     * Optional constructor, PHP does not enforce implementation
     * @param ApiRequest $request
     */
    public function __construct(ApiRequest $request);

    public function handle(ApiRequest $request): iMethodResult;
}