<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginErrorException extends Exception
{
    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'login information not valid'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
