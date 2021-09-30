# SimpleREST

Tiny package for setting up a simple REST API with PHP.

## Usage

1. Implement the iApiController interface with your controller Currently a method can return `StringResult`
   or `DataResult` which returns a message or an array along with a status code.

```php
<?php

use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;

class TestController implements iApiController
{
	public function get(ApiRequest $request): iMethodResult
	{
		return new StringResult(HttpStatusCode::SUCCESS(), 'TestController->get()');
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
```

2. Extend the `SimpleApi` class. Here you can register your routes.

```php
<?php

use Incapption\SimpleApi\SimpleApi;
use Incapption\SimpleApi\SimpleApiRoute;
use Incapption\SimpleApi\Tests\Controllers\TestController;
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
		SimpleApiRoute::registerGet('/api/currencies', TestController::class, 'get', new AuthenticationMiddleware());
	}
}
```

3. In your application root (e.g. app.php) use this controller to verify if the request is an API request, call the
   method and return the result.

```php
use Incapption\SimpleApi\Helper\HttpHeader;

$api = new SimpleApiExtension($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"], HttpHeader::getAll(), $_REQUEST);

// Check if the actual request is targeted at your API endpoint
if ($api->isApiEndpoint('api/v1'))
{
  $result = $api->getResult();
  // Returns the result as JSON and exits the application
  $api->echoResultExit($result);
}
```

The returned JSON format is like this depending on whether the Controller method returns a `StringResult`or `DataResult`
object:

`statusCode` indicates the HTTP status code
`payload` is of type `string` or `object`

```json
{
  "statusCode": 200,
  "payload": {
    "test": true
  }
}
```

```json
{
  "statusCode": 200,
  "payload": "This request was a success"
}
```

## Tests

Create a `phpunit.xml` in the project directory. Run PHPUnit referencing this xml. For example:
`php.exe ./SimpleApi/vendor/phpunit/phpunit/phpunit --configuration .\SimpleApi\phpunit.xml --teamcity`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" bootstrap="./vendor/autoload.php"
  convertErrorsToExceptions="true" convertNoticesToExceptions="true" convertWarningsToExceptions="true"
  processIsolation="false" stopOnFailure="true"
  xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.5/phpunit.xsd">
  <coverage>
    <include>
      <directory suffix=".php">src/</directory>
    </include>
  </coverage>
  <testsuites>
    <testsuite name="SimpleApi Test Suite">
      <directory>tests</directory>
    </testsuite>
  </testsuites>
</phpunit>
```
