<?php

namespace Incapption\SimpleApi\Models;

/**
 * Class ApiRequest
 */
class ApiRequest
{
	/**
	 * @var RouteParameter[]
	 */
	private $routeParameters;

	public function __construct()
	{
		$this->routeParameters = [];
	}

	/**
	 * @param RouteParameter $routeParameter
	 */
	public function add(RouteParameter $routeParameter)
	{
		$this->routeParameters[] = $routeParameter;
	}

	/**
	 * @param $param
	 * @return RouteParameter|null
	 */
	public function get($param) : ?RouteParameter
	{
		foreach ($this->routeParameters as $routeParameter)
		{
			if($routeParameter->getName() === $param)
				return $routeParameter;
		}

		return null;
	}

	/**
	 * @return RouteParameter[]|array
	 */
	public function getAll() : ?array
	{
		if(count($this->routeParameters) > 0)
			return $this->routeParameters;

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
	public function parseRouteParameters(string $route, string $requestUri)
	{
		/*
		 * Compare the backslashes
		 */
		if(substr_count($route, '/') !== substr_count($requestUri, '/'))
			return;

		/*
		 * Cut the route at each backslash
		 *
		 * e.g.
		 * /api/user/{userId}/avatar/{avatarId}
		 * ==> ['api', 'user', '{userId}', 'avatar', '{avatarId}']
		 */
		$placeholder = explode('/', $route);

		/*
		 * Cut the requestUri at each backslash
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
		 * Array
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
					$_routeParameter = new RouteParameter();
					$_routeParameter->setName($matches[1][$i]);
					$_routeParameter->setPlaceholder($placeholder[$j]);
					$_routeParameter->setValue($parameters[$j]);

					$this->add($_routeParameter);
				}
			}
		}
	}

	/**
	 * Replaces the placeholders in the route with the actual values
	 * and compare route and requestUri
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
		$routeParameters = $this->getAll();

		foreach ($routeParameters as $routeParameter)
		{
			$route = str_replace($routeParameter->getPlaceholder(), $routeParameter->getValue(), $route);
		}

		if($route === $requestUri)
			return true;

		return false;
	}
}