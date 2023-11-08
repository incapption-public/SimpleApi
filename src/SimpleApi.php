<?php

namespace Incapption\SimpleApi;

use ReflectionClass;
use ReflectionException;
use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;
use Incapption\SimpleApi\Exceptions\SimpleApiException;
use Incapption\SimpleApi\Interfaces\iWebhookController;

abstract class SimpleApi
{
    /**
     * @var string
     */
    private $requestUri;
    /**
     * @var string
     */
    private $requestMethod;
    /**
     * @var array
     */
    private $headers;
    /**
     * @var array
     */
    private $input;
    /**
     * @var ApiRequest|null
     */
    private $apiRequest;
    /**
     * @var iApiMiddleware[]
     */
    private $requestMiddlewares = [];

    /**
     * SimpleApi constructor.
     *
     * @param string $requestUri    The request Uri
     * @param string $requestMethod The HTTP Request Method
     * @param array  $headers       The request Headers (e.g. HttpHeader::getAll())
     * @param array  $input         The input of the request (e.g. $_REQUEST)
     */
    public function __construct(string $requestUri, string $requestMethod, array $headers, array $input)
    {
        $this->requestUri    = $requestUri;
        $this->requestMethod = $requestMethod;
        $this->headers       = $headers;
        $this->input         = $input;
    }

    /**
     * Abstract function for registering the API routes.
     *
     * @return void
     */
    protected abstract function registerRoutes();

    /**
     * Check whether the server REQUEST_URI includes the defined API endpoint, for example "/api/v1"
     *
     * @param string $endpoint
     *
     * @return bool
     */
    public function isApiEndpoint(string $endpoint): bool
    {
        return !empty($this->requestUri) && substr($this->requestUri, 0, strlen($endpoint)) === $endpoint;
    }

    /**
     * Get the request object. Null until a route matches the request
     *
     * @return ApiRequest|null
     */
    public function getApiRequest() : ?ApiRequest
    {
        return $this->apiRequest;
    }

    /**
     * Iterates the registered API groups and associated routes for the requested endpoint, calls the method and returns the result.
     *
     * @return iMethodResult
     * @throws ReflectionException
     * @throws SimpleApiException
     */
    public function getResult(): iMethodResult
    {
        foreach (SimpleApiRoute::getRegisteredGroups() as $group)
        {
            foreach ($group->getRoutes() as $item)
            {
                // parse route parameters and match them with values from requestUri
                $_apiRequest = new ApiRequest($this->headers, $this->input);
                $_apiRequest->parseResourceParameters($item->getRoute(), $this->requestUri);

                if ($_apiRequest->compareRouteAndRequestUri($item->getRoute(), $this->requestUri) === false ||
                    strtoupper($this->requestMethod) !== strtoupper($item->getHttpMethod()->getValue()))
                {
                    continue;
                }

                $_apiRequest->setRequestRoute($item->getRoute());
                $_apiRequest->setRequestUri($this->requestUri);
                $this->apiRequest = $_apiRequest;

                foreach ($group->getMiddlewares() as $middleware)
                {
                    $this->requestMiddlewares[] = $middleware;
                    $middleware->handle($_apiRequest);
                }

                foreach ($item->getMiddlewares() as $middleware)
                {
                    $this->requestMiddlewares[] = $middleware;
                    $middleware->handle($_apiRequest);
                }

                $controllerReflection = new ReflectionClass($item->getController());

                if ($controllerReflection->hasMethod($item->getMethod()) === false)
                {
                    throw new SimpleApiException($item->getController().'->'.$item->getMethod().'() does not exist');
                }

                if ($controllerReflection->implementsInterface(iApiController::class) === false &&
                    $controllerReflection->implementsInterface(iWebhookController::class) === false)
                {
                    throw new SimpleApiException($item->getController().' is not an API controller');
                }

                // Create the instance, constructor is optional
                $controller = $controllerReflection->newInstance($_apiRequest);

                // Call the method on the controller with ApiRequest argument
                $result = call_user_func([$controller, $item->getMethod()], $_apiRequest);

                if ($result instanceof iMethodResult)
                {
                    return $result;
                }

                throw new SimpleApiException($item->getController().'->'.$item->getMethod().'() has to return iMethodResult');
            }
        }

        return new StringResult(HttpStatusCode::NOT_FOUND(), 'Not Found: invalid api endpoint or method');
    }

    /**
     * Echoes JSON result, sets the status code and exits the application.
     *
     * @deprecated Use sendAndTerminate
     * @param iMethodResult $result
     */
    public function echoResultExit(iMethodResult $result)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($result->getStatusCode()->getValue());
        echo $result->getJson();
        exit;
    }

    /**
     * Sends status code and json result to client, then terminates middleware and exits the application.
     *
     * @param iMethodResult $result
     */
    public function sendAndTerminate(iMethodResult $result)
    {
        $this->sendHeadersAndStatusCode($result);
        $this->sendContent($result);
        $this->terminateMiddleware($result);
        exit;
    }

    private function terminateMiddleware(iMethodResult $result)
    {
        foreach ($this->requestMiddlewares as $middleware)
        {
            if (method_exists($middleware, 'terminate'))
            {
                $middleware->terminate($this->apiRequest, $result);
            }
        }
    }

    private function sendHeadersAndStatusCode(iMethodResult $result)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($result->getStatusCode()->getValue());
    }

    private function sendContent(iMethodResult $result)
    {
        echo $result->getJson();

        if (\function_exists('fastcgi_finish_request'))
        {
            fastcgi_finish_request();
        }
        elseif (\function_exists('litespeed_finish_request'))
        {
            litespeed_finish_request();
        }
        elseif (!\in_array(\PHP_SAPI, ['cli', 'phpdbg'], true))
        {
            static::closeOutputBuffers(0, true);
            flush();
        }
    }

    /**
     * Source: https://github.com/symfony/symfony/blob/6.2/src/Symfony/Component/HttpFoundation/Response.php
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @final
     */
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = ob_get_status(true);
        $level = \count($status);
        $flags = \PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? \PHP_OUTPUT_HANDLER_FLUSHABLE : \PHP_OUTPUT_HANDLER_CLEANABLE);

        while ($level-- > $targetLevel && ($s = $status[$level]) &&
            (!isset($s['del']) ? !isset($s['flags']) || ($s['flags'] & $flags) === $flags : $s['del']))
        {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }
}