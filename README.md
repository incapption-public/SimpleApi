# SimpleREST
Tiny package for setting up a simple REST API with PHP.

## Usage

```php
$client = new BunnyPurge('YOUR_BUNNY_API_KEY');
$client->purge('https://example.b-cdn.net/example.jpg');
```

`$client->purge()` throws `BunnyException` on non 200 status codes and `GuzzleException` on request exceptions.

## Tests

Create a `phpunit.xml` in the project directory and add PHP variables for your API Key and a test URL pointing to a file on your CDN.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" bootstrap="vendor/autoload.php" convertErrorsToExceptions="true" convertNoticesToExceptions="true" convertWarningsToExceptions="true" processIsolation="false" stopOnFailure="true" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
  <coverage>
    <include>
      <directory suffix=".php">src/</directory>
    </include>
  </coverage>
  <testsuites>
    <testsuite name="Project Test Suite">
      <directory>tests</directory>
    </testsuite>
  </testsuites>
  <php>
    <var name="API_KEY" value="YOUR_BUNNY_API_KEY"/>
    <var name="TEST_URL" value="https://example.b-cdn.net/example.jpg"/>
  </php>
</phpunit>
```
