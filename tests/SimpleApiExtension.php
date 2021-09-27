<?php

namespace Incapption\SimpleApi\Tests;

use Incapption\SimpleApi\SimpleApi;
use Incapption\SimpleApi\SimpleApiRoute;
use Incapption\SimpleApi\Tests\Controllers\TestController;

class SimpleApiExtension extends SimpleApi
{
	public function __construct(string $requestUri, string $requestMethod)
	{
		parent::__construct($requestUri, $requestMethod);
		$this->registerRoutes();
	}

	protected function registerRoutes()
	{
		SimpleApiRoute::registerGet('/api/user/images', TestController::class, 'get', null);
	}
}