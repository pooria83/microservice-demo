<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotFoundProductException extends Exception
{

    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'Not found product by this id'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
