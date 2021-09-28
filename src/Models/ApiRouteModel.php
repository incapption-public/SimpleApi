<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Helper\ApiRequest;
use Incapption\SimpleApi\Helper\RouteParameters;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;

class ApiRouteModel
{
	/**
	 * @var string
	 */
	private $route;

	/**
	 * @var string
	 */
	private $controller;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var iApiMiddleware|null
	 */
	private $middleware;

	/**
	 * @var HttpMethod
	 */
	private $httpMethod;

	/**
	 * @return HttpMethod
	 */
	public function getHttpMethod(): HttpMethod
	{
		return $this->httpMethod;
	}

	/**
	 * @param HttpMethod $httpMethod
	 *
	 * @return ApiRouteModel
	 */
	public function setHttpMethod(HttpMethod $httpMethod): ApiRouteModel
	{
		$this->httpMethod = $httpMethod;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRoute(): string
	{
		return $this->route;
	}

	/**
	 * @param string $route
	 *
	 * @return ApiRouteModel
	 */
	public function setRoute(string $route): ApiRouteModel
	{
		$this->route = $route;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getController(): string
	{
		return $this->controller;
	}

	/**
	 * @param string $controller
	 *
	 * @return ApiRouteModel
	 */
	public function setController(string $controller): ApiRouteModel
	{
		$this->controller = $controller;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 *
	 * @return ApiRouteModel
	 */
	public function setMethod(string $method): ApiRouteModel
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @return iApiMiddleware|null
	 */
	public function getMiddleware(): ?iApiMiddleware
	{
		return $this->middleware;
	}

	/**
	 * @param iApiMiddleware|null $middleware
	 *
	 * @return ApiRouteModel
	 */
	public function setMiddleware(?iApiMiddleware $middleware): ApiRouteModel
	{
		$this->middleware = $middleware;
		return $this;
	}

	public function compareRouteAndRequestUri(string $route, string $requestUri) : bool
	{
		$routeParameters = ApiRequest::getAll();

		foreach ($routeParameters as $key => $routeParameter)
		{
			$route = str_replace($routeParameter->getPlaceholder(), $routeParameter->getValue(), $route);
		}

		if($route === $requestUri)
			return true;

		return false;
	}
}