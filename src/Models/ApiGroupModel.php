<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Interfaces\iApiMiddleware;

class ApiGroupModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var iApiMiddleware[]
     */
    private $middlewares = [];

    /**
     * @var ApiRouteModel[]
     */
    private $routes = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ApiGroupModel
     */
    public function setName(string $name): ApiGroupModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return iApiMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param iApiMiddleware[] $middlewares
     *
     * @return ApiGroupModel
     */
    public function setMiddlewares(array $middlewares): ApiGroupModel
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
     * @return ApiRouteModel[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param ApiRouteModel $route
     *
     * @return ApiGroupModel
     */
    public function addRoute(ApiRouteModel $route): ApiGroupModel
    {
        $this->routes[] = $route;
        return $this;
    }
}