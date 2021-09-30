<?php

namespace Incapption\SimpleApi\Models;

/**
 * Class ApiRequest
 */
class ApiRequest
{
	/**
	 * @var ResourceParameter[]
	 */
	private $resourceParameters;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @var array
	 */
	private $input;

	/**
	 * ApiRequest constructor.
	 *
	 * @param array $headers The request headers
	 * @param array $input The input of $_REQUEST ($_GET, $_POST and $_COOKIE)
	 */
	public function __construct(array $headers, array $input)
	{
		$this->resourceParameters = [];
		$this->headers            = $headers;
		$this->input              = $input;
	}

	/**
	 * @param string $key The key of the input
	 *
	 * @return mixed|null
	 */
	public function input(string $key)
	{
		return array_key_exists($key, $this->input) ? $this->input[$key] : null;
	}

	/**
	 * @param string $key The key of the header
	 *
	 * @return mixed|null
	 */
	public function header(string $key)
	{
		return array_key_exists($key, $this->headers) ? $this->headers[$key] : null;
	}

	/**
	 * @param ResourceParameter $routeParameter
	 */
	private function addResourceParameter(ResourceParameter $routeParameter)
	{
		$this->resourceParameters[] = $routeParameter;
	}

	/**
	 * @param $param
	 *
	 * @return ResourceParameter|null
	 */
	public function getResourceParameter($param): ?ResourceParameter
	{
		foreach ($this->resourceParameters as $routeParameter)
		{
			if ($routeParameter->getKey() === $param)
			{
				return $routeParameter;
			}

		}

		return null;
	}

	/**
	 * @return ResourceParameter[]|array
	 */
	public function getAllResourceParameters() : ?array
	{
		if(count($this->resourceParameters) > 0)
		{
			return $this->resourceParameters;
		}


		return [];
	}

	/**
	 * This method takes a registered route and a request uri and parses the parameters
	 * In the route the placeholders are set like {userId}
	 *
	 * e.g. route      = /api/user/{userId}/avatar/{avatarId}
	 *      requestUri = /api/user/1/avatar/20
	 *
	 * userId = 1
	 * avatarId = 20
	 *
	 * @param string $route
	 * @param string $requestUri
	 */
	public function parseResourceParameters(string $route, string $requestUri)
	{
		/*
		 * Compare the slashes
		 */
		if(substr_count($route, '/') !== substr_count($requestUri, '/'))
		{
			return;
		}

		/*
		 * Cut the route at each slash
		 *
		 * e.g.
		 * /api/user/{userId}/avatar/{avatarId}
		 * ==> ['api', 'user', '{userId}', 'avatar', '{avatarId}']
		 */
		$placeholder = explode('/', $route);

		/*
		 * Cut the requestUri at each slash
		 *
		 * e.g.
		 * /api/user/1/avatar/20
		 * ==> ['api', 'user', '1', 'avatar', '20']
		 */
		$parameters  = explode('/', $requestUri);

		/*
		 * Extract all "placeholders" in between curly braces
		 *
		 * e.g.
		 * /api/user/{userId}/avatar/{avatarId}
		 * $matches = Array
		 * (
         *	[0] => Array
         *	(
         *	    [0] => {userId}
         *	    [1] => {avatarId}
         *	)
         *	[1] => Array
         *	(
         *	    [0] => userId
         *	    [1] => avatarId
         *	)
		 * )
		 */
		preg_match_all('/{(.*?)}/', $route, $matches);

		for($i = 0; $i < count($matches[0]); $i++)
		{
			// find a matching placeholder
			for($j = 0; $j < count($placeholder);$j++)
			{
				/*
				 * If a matching placeholder is found and the value is numeric
				 * create a RouteParameter and add it to the registered route parameters
				 */
				if ($placeholder[$j] === $matches[0][$i] && is_numeric($parameters[$j]))
				{
					$_routeParameter = new ResourceParameter($matches[1][$i], $parameters[$j], $placeholder[$j]);
					$this->addResourceParameter($_routeParameter);
				}
			}
		}
	}

	/**
	 * Replaces the placeholders in the route with the actual values
	 * and compare route and requestUri.
	 * Return true if they match.
	 *
	 * e.g.
	 * requestUri = /api/user/1/avatar/20
	 * route = /api/user/{userId}/avatar/{avatarId} => /api/user/1/avatar/20
	 *
	 * @param string $route
	 * @param string $requestUri
	 * @return bool
	 */
	public function compareRouteAndRequestUri(string $route, string $requestUri) : bool
	{
		$routeParameters = $this->getAllResourceParameters();

		foreach ($routeParameters as $routeParameter)
		{
			$route = str_replace($routeParameter->getPlaceholder(), $routeParameter->getValue(), $route);
		}

		if($route === $requestUri)
		{
			return true;
		}

		return false;
	}
}