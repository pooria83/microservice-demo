<?php

namespace App\Http\Middleware;

use App\Exceptions\DeletedProductException;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\NotFoundProductException;
use App\Exceptions\NotRelatedUserException;
use App\Exceptions\UserNotAdminException;
use App\Exceptions\ValidationErrorException;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CheckForUpdateAndDeleteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'required|numeric|min:1'
        ]);
        if ($validate->fails()) {
            throw new  ValidationErrorException($validate->errors()->messages());
        }


        $product = DB::table('products')
            ->whereId($request->input('product_id'))
            ->select('user_id', 'image', 'deleted_at')
            ->first();
        if ($product !== NULL) {
            $product_user_id = $product->user_id;

            if ($product_user_id != $request->input('user_id')) {

                throw new  NotRelatedUserException();
            }
            if ($product->deleted_at !== NULL) {
                throw new DeletedProductException();
            }
            $request->request->add(['product_image' => $product->image]);

            return $next($request);
        } else {
            throw new NotFoundProductException();
        }
    }
}
