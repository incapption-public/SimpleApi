<?php

namespace Incapption\SimpleApi\Tests;

use Incapption\SimpleApi\SimpleApi;
use Incapption\SimpleApi\SimpleApiRoute;
use Incapption\SimpleApi\Tests\Controllers\TestController;
use Incapption\SimpleApi\Tests\Controllers\UserController;
use Incapption\SimpleApi\Tests\Controllers\UserAvatarController;
use Incapption\SimpleApi\Tests\Middleware\AuthenticationMiddleware;

class SimpleApiExtension extends SimpleApi
{
	public function __construct(string $requestUri, string $requestMethod, array $headers = [], array $input = [])
	{
		parent::__construct($requestUri, $requestMethod, $headers, $input);
		$this->registerRoutes();
	}

	protected function registerRoutes()
	{
		$userMiddleware = new AuthenticationMiddleware();
		SimpleApiRoute::registerGet('/api/currencies', TestController::class, 'get', null);
		SimpleApiRoute::registerGet('/api/user/{userId}', UserController::class, 'get', $userMiddleware);
		SimpleApiRoute::registerGet('/api/user/{userId}/avatar/{avatarId}', UserAvatarController::class, 'get', $userMiddleware);
	}
}