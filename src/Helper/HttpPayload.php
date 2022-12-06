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
     * If Content-Type is application/json, returns JSON array + $_GET parameters
     * Otherwise returns $_POST + $_GET parameters
     *
     * @return array
     */
    public static function getInput() : array
    {
        if (self::isJsonContentType())
        {
            $content = json_decode(file_get_contents('php://input'), true);
            return is_array($content) ? $content + $_GET : $_GET;
        }

        return $_POST + $_GET;
    }
}