<?php

namespace Incapption\SimpleApi\Helper;

use Incapption\SimpleApi\Models\RouteParameter;

/**
 * Class ApiRequest
 */
class ApiRequest
{
	/**
	 * @var RouteParameter[]
	 */
	private static $routeParameters;

	/**
	 * @return void
	 */
	public static function reset()
	{
		self::$routeParameters = [];
	}

	/**
	 * @param RouteParameter $routeParameter
	 */
	public static function add(RouteParameter $routeParameter)
	{
		self::$routeParameters[] = $routeParameter;
	}

	/**
	 * @param $param
	 * @return RouteParameter|null
	 */
	public static function get($param) : ?RouteParameter
	{
		foreach (self::$routeParameters as $routeParameter)
		{
			if($routeParameter->getName() === $param)
				return $routeParameter;
		}

		return null;
	}

	/**
	 * @return RouteParameter[]|array
	 */
	public static function getAll() : ?array
	{
		if(count(self::$routeParameters) > 0)
			return self::$routeParameters;

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
	public static function parseRouteParameters(string $route, string $requestUri)
	{
		self::reset();

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

					self::add($_routeParameter);
				}
			}
		}
	}
}