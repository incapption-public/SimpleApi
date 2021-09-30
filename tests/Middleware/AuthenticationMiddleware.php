<?php

namespace Incapption\SimpleApi\Tests\Middleware;

use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;
use Incapption\SimpleApi\Exceptions\SimpleApiException;

class AuthenticationMiddleware implements iApiMiddleware
{
	/**
	 * @throws SimpleApiException
	 */
	public function handle(ApiRequest $apiRequest)
	{
		if ($apiRequest->header('X-AUTH-TOKEN') === null || $apiRequest->header('X-AUTH-TOKEN') !== 'VALID')
		{
			throw new SimpleApiException('Unauthorized');
		}
	}
}