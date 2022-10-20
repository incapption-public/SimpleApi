<?php

namespace Incapption\SimpleApi\Helper;

/**
 * Class HttpHeader
 * Helper class for handling http headers
 */
class HttpHeader
{
    /**
     * Fetches all http headers in upper case and returns an array
     *
     * @return array
     */
    public static function getAll(): array
    {
        $header = [];

        foreach (getallheaders() as $name => $value)
        {
            $header[strtoupper($name)] = $value;
        }

        return $header;
    }

    /**
     * fetches a specific http header and returns its value
     *
     * @param $name string Case insensitive header name
     *
     * @return ?string header value or null if header is not set
     */
    public static function get(string $name): ?string
    {
        $header = self::getAll();

        if (empty($header[strtoupper($name)]))
            return null;

        return $header[$name];
    }
}