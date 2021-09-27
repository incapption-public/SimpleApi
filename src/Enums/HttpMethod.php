<?php

namespace Incapption\SimpleRest\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self GET()
 * @method static self POST()
 * @method static self PUT()
 * @method static self PATCH()
 * @method static self DELETE()
 */
class HttpMethod extends Enum
{
	private const GET = 'GET';
	private const POST = 'POST';
	private const PUT = 'PUT';
	private const PATCH = 'PATCH';
	private const DELETE = 'DELETE';
}