<?php

namespace App\Exceptions;

class UnauthorizedException extends BaseException
{
    protected $statusCode = 401;
}
