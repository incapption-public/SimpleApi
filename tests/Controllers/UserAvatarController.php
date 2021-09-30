<?php

namespace Incapption\SimpleApi\Tests\Controllers;

use PHPUnit\Framework\MockObject\Api;
use Incapption\SimpleApi\Models\DataResult;
use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;

class UserAvatarController implements iApiController
{
	public function get(ApiRequest $request): iMethodResult
	{
		return new DataResult(HttpStatusCode::SUCCESS(), [
			'userId' => $request->getResourceParameter('userId')->getValue(),
			'avatarId' => $request->getResourceParameter('avatarId')->getValue()
		]);
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