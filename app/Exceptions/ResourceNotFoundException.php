<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class ResourceNotFoundException extends BaseException
{
    protected $statusCode = 404;
}
