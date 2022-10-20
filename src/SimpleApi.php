<?php

namespace Incapption\SimpleApi;

use ReflectionClass;
use ReflectionException;
use Incapption\SimpleApi\Models\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Interfaces\iMethodResult;
use Incapption\SimpleApi\Interfaces\iApiController;
use Incapption\SimpleApi\Exceptions\SimpleApiException;

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

                foreach ($group->getMiddlewares() as $middleware)
                {
                    $middleware->handle($_apiRequest);
                }

                foreach ($item->getMiddlewares() as $middleware)
                {
                    $middleware->handle($_apiRequest);
                }

                $controllerReflection = new ReflectionClass($item->getController());

                if ($controllerReflection->hasMethod($item->getMethod()) === false)
                {
                    throw new SimpleApiException($item->getController().'->'.$item->getMethod().'() does not exist');
                }

                if ($controllerReflection->implementsInterface(iApiController::class) === false)
                {
                    throw new SimpleApiException($item->getController().' is not an API controller');
                }

                $controller = $controllerReflection->newInstance();

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
     * @param iMethodResult $result
     */
    public function echoResultExit(iMethodResult $result)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($result->getStatusCode()->getValue());
        echo $result->getJson();
        exit;
    }
}