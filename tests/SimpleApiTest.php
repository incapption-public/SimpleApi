<?php

namespace Incapption\SimpleApi\Tests;

use PHPUnit\Framework\TestCase;
use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Exceptions\SimpleApiException;

class SimpleApiTest extends TestCase
{
    private $requestUserHeaders = ['X-AUTH-TOKEN' => 'VALID'];

    /** @test */
    public function api_controller_returns_valid_result()
    {
        $api    = new SimpleApiExtension('/api/currencies', HttpMethod::GET()->getValue());
        $result = $api->getResult();

        $this->assertInstanceOf(iMethodResult::class, $result);
        $this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
    }

    /** @test */
    public function returns_not_found_for_undefined_controller_method()
    {
        $api    = new SimpleApiExtension('/api/currencies', 'getInvalid');
        $result = $api->getResult();

        $this->assertEquals(404, $result->getStatusCode()->getValue(), 'Assert status code is 404');
    }

    /** @test */
    public function returns_not_found_for_undefined_route()
    {
        $api    = new SimpleApiExtension('/api/currencies/exchangeRates', 'get');
        $result = $api->getResult();

        $this->assertEquals(404, $result->getStatusCode()->getValue(), 'Assert status code is 404');
    }

    /** @test */
    public function throws_exception_when_get_user_without_headers()
    {
        $this->expectException(SimpleApiException::class);

        $api = new SimpleApiExtension('/api/user/1', 'get');
        $api->getResult();
    }

    /** @test */
    public function throws_exception_when_get_user_with_invalid_key()
    {
        $this->expectException(SimpleApiException::class);

        $api = new SimpleApiExtension('/api/user/1', 'get', ['X-AUTH-TOKEN' => 'INVALID']);
        $api->getResult();
    }

    /** @test */
    public function get_user_with_id_authorized()
    {
        $api    = new SimpleApiExtension('/api/user/1', 'get', $this->requestUserHeaders);
        $result = $api->getResult();
        $data   = json_decode($result->getJson(), true);

        $this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
        $this->assertEquals(1, $data['id'], 'Assert user id is 1');
    }

    /** @test */
    public function get_user_with_hashId_authorized()
    {
        $api    = new SimpleApiExtension('/api/user/54gdf45fsd', 'get', $this->requestUserHeaders);
        $result = $api->getResult();
        $data   = json_decode($result->getJson(), true);

        $this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
        $this->assertEquals('54gdf45fsd', $data['id'], 'Assert user id is 54gdf45fsd');
    }

    /** @test */
    public function get_user_and_avatar_with_ids()
    {
        $api    = new SimpleApiExtension('/api/user/1/avatar/20', 'get', $this->requestUserHeaders);
        $result = $api->getResult();
        $data   = json_decode($result->getJson(), true);

        $this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
        $this->assertEquals(1, $data['userId'], 'Assert user id is 1');
        $this->assertEquals(20, $data['avatarId'], 'Assert avatar id is 20');
    }

    /** @test */
    public function get_user_with_id_authenticated()
    {
        $api    = new SimpleApiExtension('/api/user/1', 'get', $this->requestUserHeaders);
        $result = $api->getResult();
        $data   = json_decode($result->getJson(), true);

        $this->assertEquals(200, $result->getStatusCode()->getValue(), 'Assert status code is 200');
        $this->assertEquals(1, $data['id'], 'Assert user id is 1');
    }
}