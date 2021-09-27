<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpStatusCode;

abstract class MethodResult
{
	/**
	 * @var HttpStatusCode
	 */
	protected $statusCode;

	protected const JSON_KEY_STATUS_CODE = 'statusCode';
	protected const JSON_KEY_PAYLOAD = 'payload';

	public function getStatusCode(): HttpStatusCode
	{
		return $this->statusCode;
	}

	abstract function getJson() : string;
}