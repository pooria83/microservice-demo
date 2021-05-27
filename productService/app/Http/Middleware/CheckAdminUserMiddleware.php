<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\UserNotAdminException;
use App\Exceptions\ValidationErrorException;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CheckAdminUserMiddleware
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

        $user_type = DB::table('users')
        ->select('user_type')
        ->where('id' , $request->input('user_id'))
        ->first()
        ->user_type;
        if($user_type ==2)
        {
           throw new UserNotAdminException() ;
        }

        return $next($request);
    }
}
