<?php

namespace Incapption\SimpleApi\Interfaces;

use Incapption\SimpleApi\Models\ApiRequest;

interface iApiController
{
    /**
     * Optional constructor, PHP does not enforce implementation
     * @param ApiRequest $request
     */
    public function __construct(ApiRequest $request);

    public function get(ApiRequest $request): iMethodResult;

    public function index(ApiRequest $request): iMethodResult;

    public function create(ApiRequest $request): iMethodResult;

    public function update(ApiRequest $request): iMethodResult;

    public function delete(ApiRequest $request): iMethodResult;
}