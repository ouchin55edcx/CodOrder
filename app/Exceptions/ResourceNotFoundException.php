<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => $this->message
        ], 404);
    }
}
