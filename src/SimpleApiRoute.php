<?php

namespace Incapption\SimpleApi;

use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Models\ApiRouteModel;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;

class SimpleApiRoute
{
    /**
     * @var ApiRouteModel[]
     */
    private static $routes = [];

    /**
     * Register an API route
     *
     * @param string              $route
     * @param HttpMethod          $httpMethod
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    private static function register(string $route, HttpMethod $httpMethod, string $controller,
                                     string $method, iApiMiddleware $middleware = null)
    {
        self::$routes[] = (new ApiRouteModel())
            ->setRoute($route)
            ->setHttpMethod($httpMethod)
            ->setController($controller)
            ->setMethod($method)
            ->setMiddleware($middleware);
    }

    /**
     * Register a GET route
     *
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    public static function registerGet(string $route, string $controller, string $method, iApiMiddleware $middleware = null)
    {
        self::register($route, HttpMethod::GET(), $controller, $method, $middleware);
    }

    /**
     * Register a POST route
     *
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    public static function registerPost(string $route, string $controller, string $method, iApiMiddleware $middleware = null)
    {
        self::register($route, HttpMethod::POST(), $controller, $method, $middleware);
    }

    /**
     * Register a PUT route
     *
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    public static function registerPut(string $route, string $controller, string $method, iApiMiddleware $middleware = null)
    {
        self::register($route, HttpMethod::PUT(), $controller, $method, $middleware);
    }

    /**
     * Register a PATCH route
     *
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    public static function registerPatch(string $route, string $controller, string $method, iApiMiddleware $middleware = null)
    {
        self::register($route, HttpMethod::PATCH(), $controller, $method, $middleware);
    }

    /**
     * Register a DELETE route
     *
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware|null $middleware
     */
    public static function registerDelete(string $route, string $controller, string $method, iApiMiddleware $middleware = null)
    {
        self::register($route, HttpMethod::DELETE(), $controller, $method, $middleware);
    }

    /**
     * @return ApiRouteModel[]
     */
    public static function getRegisteredRoutes(): array
    {
        return self::$routes;
    }
}