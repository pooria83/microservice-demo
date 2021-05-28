<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeletedProductException extends Exception
{

    public function render($request)
    {
        return  new JsonResponse([
            'status' => false,
            'result' => 'This product has already been deleted!'
        ], Response::HTTP_UNAUTHORIZED );
    }
}
