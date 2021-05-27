<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\ValidationErrorException;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ApiTokenMiddleware
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
            'token' => 'required|string|min:100|max:100'
        ]);
        if ($validate->fails()) {
            throw new  ValidationErrorException($validate->errors()->messages());
        }
        $token = $request->input('token');
        $user =  DB::table('users')
            ->select('id')
            ->where('api_token', $token)
            ->first();
        if (isset($user->id) && $user->id > 0) {
            $request->request->add(['user_id' => $user->id]);
        } else {
           throw new InvalidTokenException();
        }


        return $next($request);
    }
}
