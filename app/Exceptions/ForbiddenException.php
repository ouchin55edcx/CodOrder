<?php

namespace App\Exceptions;

class ForbiddenException extends BaseException
{
    protected $statusCode = 403;
}
