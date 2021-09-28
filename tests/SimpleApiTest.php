<?php

namespace Incapption\SimpleApi\Tests;

use PHPUnit\Framework\TestCase;
use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Interfaces\iMethodResult;

class SimpleApiTest extends TestCase
{
	protected function setUp(): void
	{

	}

	/** @test */
	public function api_controller_returns_valid_result()
	{
		$api = new SimpleApiExtension('/api/user/images', HttpMethod::GET()->getValue());
		$result = $api->getResult();

		$this->assertInstanceOf(iMethodResult::class, $result);
		$this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
	}

	/** @test */
	public function returns_not_found_for_undefined_controller_method()
	{
		$api = new SimpleApiExtension('/api/user/images', 'getInvalid');
		$result = $api->getResult();

		$this->assertEquals(404, $result->getStatusCode()->getValue(), 'Assert status code is 404');
	}

	/** @test */
	public function get_user_with_id()
	{
		$api = new SimpleApiExtension('/api/user/1', 'get');
		$result = $api->getResult();
		$data = json_decode($result->getJson(), true);

		$this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
		$this->assertEquals(1, $data['payload'], 'Assert user id is 1');
	}

	/** @test */
	public function get_user_and_avatar_with_ids()
	{
		$api = new SimpleApiExtension('/api/user/1/avatar/20', 'get');
		$result = $api->getResult();
		$data = json_decode($result->getJson(), true);
		$data = json_decode($data['payload'], true);

		$this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
		$this->assertEquals(1, $data['userId'], 'Assert user id is 1');
		$this->assertEquals(20, $data['avatarId'], 'Assert avatar id is 20');
	}
}