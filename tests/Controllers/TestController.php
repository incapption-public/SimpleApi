<?php

namespace Incapption\SimpleApi\Tests\Controllers;

use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;

class TestController implements iApiController
{
	public function get(): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->get()');
	}

	public function index(): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->index()');
	}

	public function create(): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->create()');
	}

	public function update(): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->update()');
	}

	public function delete(): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->delete()');
	}
}