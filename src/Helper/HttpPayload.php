<?php

namespace Incapption\SimpleApi\Helper;

/**
 * Class HttpPayload
 * Helper class for handling http payload (Content-Type, $_POST, JSON etc.)
 */
class HttpPayload
{
    /**
     * Returns if http header Content-Type is application/json
     *
     * @return bool
     */
    public static function isJsonContentType() : bool
    {
        return substr(HttpHeader::get('CONTENT-TYPE'), 0, strlen('application/json')) === 'application/json';
    }

    /**
     * Returns given HTTP Input depending on Content-Type
     *
     * If Content-Type is application/json, returns JSON + $_GET parameters
     * Otherwise returns $_POST + $_GET parameters
     *
     * @return array
     */
    public static function getInput() : array
    {
        return self::isJsonContentType() ? json_decode(file_get_contents('php://input'), true) + $_GET :
            $_POST + $_GET;
    }
}