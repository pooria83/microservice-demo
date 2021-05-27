<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserNotAdminException extends Exception
{

    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'Marketing user cannot submit product!'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
