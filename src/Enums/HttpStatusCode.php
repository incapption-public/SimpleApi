<?php

namespace Incapption\SimpleApi\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self SUCCESS()
 * @method static self BAD_REQUEST()
 * @method static self UNAUTHORIZED()
 * @method static self FORBIDDEN()
 * @method static self NOT_FOUND()
 * @method static self UNPROCESSABLE_ENTITY()
 * @method static self SERVER_ERROR()
 * @method static self NOT_IMPLEMENTED()
 * @method static self SERVICE_UNAVAILABLE()
 */
class HttpStatusCode extends Enum
{
    private const SUCCESS = 200;
    private const BAD_REQUEST = 400;
    private const UNAUTHORIZED = 401;
    private const FORBIDDEN = 403;
    private const NOT_FOUND = 404;
    private const UNPROCESSABLE_ENTITY = 422;
    private const SERVER_ERROR = 500;
    private const NOT_IMPLEMENTED = 501;
    private const SERVICE_UNAVAILABLE = 503;
}