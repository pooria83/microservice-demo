<?php

namespace App\Http\Middleware;

use App\Exceptions\DbErrorException;
use App\Exceptions\DeletedProductException;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\NotFoundProductException;
use App\Exceptions\ValidationErrorException;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ShowProductMiddleware
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
        try {
            $product = DB::table('products')
                ->whereId($request->input('product_id'))
                ->first();
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }

        if ($product === null) {
            throw new NotFoundProductException();
        }
        try {
            $view_check = DB::table('product_user')
                ->where('user_id', $request->input('user_id'))
                ->where('product_id', $request->input('product_id'))
                ->first();
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        if ($view_check === null) {
            $data = [
                'user_id' => $request->input('user_id'),
                'product_id' => $request->input('product_id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            try {
                DB::table('product_user')
                    ->insert($data);
            } catch (QueryException $exception) {
                throw new DbErrorException($exception->errorInfo);
            }
        }

        return $next($request);
    }
}
