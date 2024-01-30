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
    private $headersLowerCaseKeys;

    /**
     * @var array
     */
    private $input;

    /**
     * @var array
     */
    private $inputLowerCaseKeys;

    /**
     * @var string|null
     */
    private $requestUri;

    /**
     * @var string|null
     */
    private $requestRoute;

    /**
     * ApiRequest constructor.
     *
     * @param array $headers The request headers, case-insensitive
     * @param array $input   The input of the request (e.g. $_REQUEST)
     */
    public function __construct(array $headers, array $input)
    {
        $this->resourceParameters   = [];
        $this->headers              = $headers;
        $this->headersLowerCaseKeys = array_change_key_case($this->headers);
        $this->input                = $input;
        $this->inputLowerCaseKeys   = array_change_key_case($this->input);
    }

    /**
     * Indicates whether a given input exits.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasInput(string $key) : bool
    {
        $key = strtolower($key);
        return array_key_exists($key, $this->inputLowerCaseKeys);
    }

    /**
     * @param string $key The case-insensitive key of the input
     *
     * @return mixed|null
     */
    public function input(string $key)
    {
        $key = strtolower($key);
        return array_key_exists($key, $this->inputLowerCaseKeys) ? $this->inputLowerCaseKeys[$key] : null;
    }

    /**
     * Merge new input into the current request's input array.
     *
     * @param array $input
     *
     * @return void
     */
    public function merge(array $input)
    {
        $this->input = array_merge_recursive($this->input, $input);
        $this->inputLowerCaseKeys = array_change_key_case($this->input);
    }

    /**
     * Overwrite the current request's input array recursively.
     *
     * @param array $input
     *
     * @return void
     */
    public function overwrite(array $input)
    {
        $this->input = $this->arrayOverwriteRecursive($this->input, $input);
        $this->inputLowerCaseKeys = array_change_key_case($this->input);
    }

    /**
     * Recursively overwrite elements from passed arrays into the first array.
     *
     * @param array $array1 The array to be overwritten.
     * @param array $array2 The array with values to overwrite.
     *
     * @return array
     */
    private function arrayOverwriteRecursive(array &$array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1) && is_array($value)) {
                $array1[$key] = $this->arrayOverwriteRecursive($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }

    /**
     * Returns all inputs / payload of this request
     *
     * @param bool $lowerCaseKeys Returns the input array with keys converted to lower case
     *
     * @return array
     */
    public function inputs(bool $lowerCaseKeys = false) : array
    {
        return $lowerCaseKeys ? $this->inputLowerCaseKeys : $this->input;
    }

    /**
     * Indicates whether a given header exits.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasHeader(string $key) : bool
    {
        $key = strtolower($key);
        return array_key_exists($key, $this->headersLowerCaseKeys);
    }

    /**
     * @param string $key The key of the header, case insensitive
     *
     * @return mixed|null
     */
    public function header(string $key)
    {
        $key = strtolower($key);
        return array_key_exists($key, $this->headersLowerCaseKeys) ? $this->headersLowerCaseKeys[$key] : null;
    }

    /**
     * Returns all headers of this request
     *
     * @param bool $lowerCaseKeys Returns the input array with keys converted to lower case
     *
     * @return array
     */
    public function headers(bool $lowerCaseKeys = false) : array
    {
        return $lowerCaseKeys ? $this->headersLowerCaseKeys : $this->headers;
    }

    /**
     * Get the Uri of this request.
     *
     * @return string|null
     */
    public function getRequestUri(): ?string
    {
        return $this->requestUri;
    }

    /**
     * @param string $requestUri
     *
     * @return ApiRequest
     */
    public function setRequestUri(string $requestUri): ApiRequest
    {
        $this->requestUri = $requestUri;
        return $this;
    }

    /**
     * Get the route of this request, as registered in the API definition
     *
     * @return string|null
     */
    public function getRequestRoute(): ?string
    {
        return $this->requestRoute;
    }

    /**
     * @param string $requestRoute
     *
     * @return ApiRequest
     */
    public function setRequestRoute(string $requestRoute): ApiRequest
    {
        $this->requestRoute = $requestRoute;
        return $this;
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
    public function getAllResourceParameters(): ?array
    {
        if (count($this->resourceParameters) > 0)
        {
            return $this->resourceParameters;
        }


        return [];
    }

    /**
     * This method takes a registered route and a request uri and parses the parameters.
     * The placeholders are set like {userId} in the route
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
        if (substr_count($route, '/') !== substr_count($requestUri, '/'))
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
        $parameters = explode('/', $requestUri);

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

        for ($i = 0; $i < count($matches[0]); $i++)
        {
            // find a matching placeholder
            for ($j = 0; $j < count($placeholder); $j++)
            {
                /*
                 * If a matching placeholder is found
                 * create a RouteParameter and add it to the registered route parameters.
                 */
                if ($placeholder[$j] === $matches[0][$i])
                {
                    $_routeParameter = new ResourceParameter($matches[1][$i], $parameters[$j], $placeholder[$j]);
                    $this->addResourceParameter($_routeParameter);
                }
            }
        }
    }

    /**
     * Replaces the placeholders in the route with the actual values
     * and compare route and requestUri. Return true if they match.
     *
     * e.g.
     * requestUri = /api/user/1/avatar/20
     * route = /api/user/{userId}/avatar/{avatarId} => /api/user/1/avatar/20
     *
     * @param string $route
     * @param string $requestUri
     *
     * @return bool
     */
    public function compareRouteAndRequestUri(string $route, string $requestUri): bool
    {
        $routeParameters = $this->getAllResourceParameters();

        foreach ($routeParameters as $routeParameter)
        {
            $route = str_replace($routeParameter->getPlaceholder(), $routeParameter->getValue(), $route);
        }

        $parsedUrl = parse_url($requestUri);

        // Compare the route with the requestUri without a possible query string
        if ($route === $parsedUrl['path'])
        {
            return true;
        }

        return false;
    }
}