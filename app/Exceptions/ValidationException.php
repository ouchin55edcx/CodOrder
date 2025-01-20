<?php

namespace App\Exceptions;

class ValidationException extends BaseException
{
    protected $statusCode = 422;
}
