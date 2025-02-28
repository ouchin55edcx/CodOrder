<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;

class RegistrationResponse extends JsonResponse
{
    public function __construct($message, $status = 200)
    {
        parent::__construct([
            'message' => $message,
        ], $status);
    }
}