<?php

namespace Incapption\SimpleRest;

use RuntimeException;
use Incapption\SimpleRest\Enums\HttpMethod;
use Incapption\SimpleRest\Models\ApiRouteModel;
use Incapption\SimpleRest\Interfaces\iApiController;
use Incapption\SimpleRest\Interfaces\iApiMiddleware;

class SimpleApiRoute
{
	/**
	 * @var ApiRouteModel[]
	 */
	private static $register = [];

    private static function register(string $route, HttpMethod $httpMethod, string $controller,
                                     string $method, iApiMiddleware $middleware = null)
    {
        self::$register[] = (new ApiRouteModel())
	        ->setRoute($route)
	        ->setHttpMethod($httpMethod)
	        ->setController($controller)
            ->setMethod($method)
            ->setMiddleware($middleware);
    }

    public static function get(string $route, string $controller, string $processedMethod, iApiMiddleware $middleware)
    {
        self::register($route, HttpMethod::GET(), $controller, $processedMethod, $middleware);
    }

    public static function post(string $route, string $controller, string $processedMethod, iApiMiddleware $middleware)
    {
        self::register($route, HttpMethod::POST(), $controller, $processedMethod, $middleware);
    }

    public static function put(string $route, string $controller, string $processedMethod, iApiMiddleware $middleware)
    {
        self::register($route, HttpMethod::PUT(), $controller, $processedMethod, $middleware);
    }

    public static function patch(string $route, string $controller, string $processedMethod, iApiMiddleware $middleware)
    {
        self::register($route, HttpMethod::PATCH(), $controller, $processedMethod, $middleware);
    }

    public static function delete(string $route, string $controller, string $processedMethod, iApiMiddleware $middleware)
    {
        self::register($route, HttpMethod::DELETE(), $controller, $processedMethod, $middleware);
    }

    public static function processRoute(string $route)
    {
        foreach (self::$register as $item)
        {
        	if ($item->getRoute() !== $route && strtoupper($_SERVER['REQUEST_METHOD']) !==
		        strtoupper($item->getHttpMethod()->getValue()))
	        {
	        	continue;
	        }

        	if ($middleware = $item->getMiddleware())
	        {
	            $middleware->authorize();
	        }

        	if (method_exists($item->getController(), $item->getMethod()) === false)
	        {
	        	throw new RuntimeException($item->getController().'->'.$item->getMethod().'() does not exist');
	        }

            $response = new ($item->getController())->{$item->getMethod()}();

            if (is_array($response))
                echo json_encode($response);

            exit;
        }

        \RestApiResponse::notFound();
    }
}