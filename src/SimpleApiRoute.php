<?php

namespace Incapption\SimpleApi;

use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Models\ApiRouteModel;
use Incapption\SimpleApi\Models\ApiGroupModel;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;
use Incapption\SimpleApi\Exceptions\SimpleApiException;

class SimpleApiRoute
{
    /**
     * @var ApiGroupModel[]
     */
    private static $groups = [];

    /**
     * Register an API route
     *
     * @param string           $groupName
     * @param string           $route
     * @param HttpMethod       $httpMethod
     * @param string           $controller
     * @param string           $method
     * @param iApiMiddleware[] $middlewares
     */
    private static function register(string $groupName, string $route, HttpMethod $httpMethod, string $controller,
                                     string $method, array $middlewares)
    {
        $_route = (new ApiRouteModel())
            ->setRoute($route)
            ->setHttpMethod($httpMethod)
            ->setController($controller)
            ->setMethod($method)
            ->addMiddlewares($middlewares);

        foreach (self::$groups as $groupModel)
        {
            if (strcasecmp($groupModel->getName(), $groupName) !== 0)
            {
                continue;
            }

            $groupModel->addRoute($_route);

            return;
        }

        self::$groups[] = (new ApiGroupModel())
            ->setName($groupName)
            ->addRoute($_route);
    }

    /**
     * Register an API group
     *
     * If a group with the same name already exists, the middelwares of that group will be replaced.
     *
     * @param string $groupName
     * @param array  $middlewares
     *
     */
    public static function registerGroup(string $groupName, array $middlewares = [])
    {
        foreach (self::$groups as $group)
        {
            if (strcasecmp($group->getName(), $groupName) === 0)
            {
                $group->setMiddlewares($middlewares);
                return;
            }
        }

        self::$groups[] = (new ApiGroupModel())
            ->setName($groupName)
            ->setMiddlewares($middlewares);
    }

    /**
     * Register a GET route
     *
     * @param string              $groupName
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware[]    $middlewares
     */
    public static function registerGet(string $groupName, string $route, string $controller, string $method, array $middlewares = [])
    {
        self::register($groupName, $route, HttpMethod::GET(), $controller, $method, $middlewares);
    }

    /**
     * Register a POST route
     *
     * @param string              $groupName
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware[]    $middlewares
     */
    public static function registerPost(string $groupName, string $route, string $controller, string $method, array $middlewares = [])
    {
        self::register($groupName, $route, HttpMethod::POST(), $controller, $method, $middlewares);
    }

    /**
     * Register a PUT route
     *
     * @param string              $groupName
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware[]    $middlewares
     */
    public static function registerPut(string $groupName, string $route, string $controller, string $method, array $middlewares = [])
    {
        self::register($groupName, $route, HttpMethod::PUT(), $controller, $method, $middlewares);
    }

    /**
     * Register a PATCH route
     *
     * @param string              $groupName
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware[]    $middlewares
     */
    public static function registerPatch(string $groupName, string $route, string $controller, string $method, array $middlewares = [])
    {
        self::register($groupName, $route, HttpMethod::PATCH(), $controller, $method, $middlewares);
    }

    /**
     * Register a DELETE route
     *
     * @param string              $groupName
     * @param string              $route
     * @param string              $controller
     * @param string              $method
     * @param iApiMiddleware[]    $middlewares
     */
    public static function registerDelete(string $groupName, string $route, string $controller, string $method, array $middlewares = [])
    {
        self::register($groupName, $route, HttpMethod::DELETE(), $controller, $method, $middlewares);
    }

    /**
     * Get all registered groups
     *
     * @return ApiGroupModel[]
     */
    public static function getRegisteredGroups(): array
    {
        return self::$groups;
    }

    /**
     * Get all registered API routes
     *
     * @return ApiRouteModel[]
     */
    public static function getRegisteredRoutes(): array
    {
        $routes = [];

        foreach (self::$groups as $group)
        {
            array_push($routes, $group->getRoutes());
        }

        return $routes;
    }
}