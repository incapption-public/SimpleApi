<?php

namespace Incapption\SimpleRest\Helper;

/**
 * Class HttpHeader
 * Helper class for handling http headers
 */
class HttpHeader
{
    /**
     * Fetches all http headers and returns an array
     * @return array
     */
    public static function getAll(): array
    {
        $header = [];

        foreach (getallheaders() as $name => $value) {
            $header[mb_strtoupper($name)] = $value;
        }

        return $header;
    }

    /**
     * fetches a specfic http header and returns its value
     *
     * @param $name
     *
     * @return string|bool
     */
    public static function get($name)
    {
        $header = self::getAll();

        if(empty($header[$name]))
            return false;

        return $header[$name];
    }
}