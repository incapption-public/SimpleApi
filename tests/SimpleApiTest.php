<?php

namespace Incapption\SimpleApi\Tests;

use PHPUnit\Framework\TestCase;
use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class SimpleApiTest extends TestCase
{
	protected function setUp(): void
	{
		include_once('SimpleApiExtension.php');
	}

	public function testApiControllerReturnsValidResult()
	{
		$api = new SimpleApiExtension('/api/user/images', HttpMethod::GET()->getValue());
		$result = $api->getResult();

		$this->assertInstanceOf(iMethodResult::class, $result);
		$this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
	}

	public function testReturnsNotFoundForUndefinedControllerMethod()
	{
		$api = new SimpleApiExtension('/api/user/images', 'getInvalid');
		$result = $api->getResult();

		$this->assertEquals(404, $result->getStatusCode()->getValue(), 'Assert status code is 404');
	}
}