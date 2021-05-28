<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotRelatedUserException extends Exception
{

    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'This user not owner of product!'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
