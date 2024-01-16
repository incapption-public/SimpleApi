<?php

namespace Incapption\SimpleApi\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self SUCCESS()
 * @method static self CREATED()
 * @method static self BAD_REQUEST()
 * @method static self UNAUTHORIZED()
 * @method static self PAYMENT_REQUIRED()
 * @method static self FORBIDDEN()
 * @method static self NOT_FOUND()
 * @method static self METHOD_NOT_ALLOWED()
 * @method static self NOT_ACCEPTABLE()
 * @method static self CONFLICT()
 * @method static self GONE()
 * @method static self UNPROCESSABLE_ENTITY()
 * @method static self UPGRADE_REQUIRED()
 * @method static self TOO_MANY_REQUESTS()
 * @method static self SERVER_ERROR()
 * @method static self NOT_IMPLEMENTED()
 * @method static self SERVICE_UNAVAILABLE()
 */
class HttpStatusCode extends Enum
{
    private const SUCCESS = 200;
    private const CREATED = 201;
    private const BAD_REQUEST = 400;
    private const UNAUTHORIZED = 401;
    private const PAYMENT_REQUIRED = 402;
    private const FORBIDDEN = 403;
    private const NOT_FOUND = 404;
    private const METHOD_NOT_ALLOWED = 405;
    private const NOT_ACCEPTABLE = 406;
    private const CONFLICT = 409;
    private const GONE = 410;
    private const UNPROCESSABLE_ENTITY = 422;
    private const UPGRADE_REQUIRED = 426;
    private const TOO_MANY_REQUESTS = 429;
    private const SERVER_ERROR = 500;
    private const NOT_IMPLEMENTED = 501;
    private const SERVICE_UNAVAILABLE = 503;
}