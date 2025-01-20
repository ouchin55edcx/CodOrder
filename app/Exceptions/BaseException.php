<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected $statusCode = 500;

    public function render($request)
    {
        return response()->json([
            'error' => $this->message
        ], $this->statusCode);
    }
}
