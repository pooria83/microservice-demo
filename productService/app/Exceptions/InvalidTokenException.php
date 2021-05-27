<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidTokenException extends Exception
{

    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'Invalid Token'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
