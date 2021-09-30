<?php

namespace Incapption\SimpleApi\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self SUCCESS()
 * @method static self UNAUTHORIZED()
 * @method static self NOT_FOUND()
 * @method static self UNPROCESSABLE_ENTITY()
 */
class HttpStatusCode extends Enum
{
    private const SUCCESS = 200;
    private const UNAUTHORIZED = 401;
    private const NOT_FOUND = 404;
    private const UNPROCESSABLE_ENTITY = 422;
}