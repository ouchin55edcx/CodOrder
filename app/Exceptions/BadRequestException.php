<?php

namespace App\Exceptions;

class BadRequestException extends BaseException
{
    protected $statusCode = 400;
}
