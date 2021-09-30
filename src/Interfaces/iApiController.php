<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iApiController
{
    public function get(ApiRequest $request): iMethodResult;

    public function index(ApiRequest $request): iMethodResult;

    public function create(ApiRequest $request): iMethodResult;

    public function update(ApiRequest $request): iMethodResult;

    public function delete(ApiRequest $request): iMethodResult;
}