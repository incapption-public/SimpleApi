<?php

namespace Incapption\SimpleApi\Tests\Controllers;

use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;
use Incapption\SimpleApi\Interfaces\iConstructableApiController;

class TestController implements iApiController, iConstructableApiController
{
    public function __construct(ApiRequest $request)
    {
    }

    public function get(ApiRequest $request): iMethodResult
    {
        return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->get()');
    }

    public function index(ApiRequest $request): iMethodResult
    {
        return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->index()');
    }

    public function create(ApiRequest $request): iMethodResult
    {
        return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->create()');
    }

    public function update(ApiRequest $request): iMethodResult
    {
        return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->update()');
    }

    public function delete(ApiRequest $request): iMethodResult
    {
        return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->delete()');
    }
}