<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\DbErrorException;
use App\Exceptions\LoginErrorException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{


    public function login(Request $request)
    {
        //Data validation
        $validate = Validator::make($request->all(), [
            'email' => 'required|exists:users|email|',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            throw new ValidationErrorException($validate->errors()->messages());
        }

        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        //Check login information
        $user = User::whereEmail($data['email'])->firstOrFail();
        if (!Hash::check($data['password'], $user->password)) {
            throw new LoginErrorException();
        }


        return [
            'status' => true,
            'result' => [
                'api_token' => $user->api_token
            ]
        ];
        //return response

    }

    public function register(Request $request)
    {
        $validate =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'user_type' => 'required|numeric|min:1|max:2'
        ]);


        if ($validate->fails()) {
            throw new ValidationErrorException($validate->errors()->messages());
        }

        try {

            $user = User::create([
                'user_type' => $request->input('user_type'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'api_token' => Str::random(100),
            ]);
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }

        return [
            'status' => true,
            'result' => [
                'message' => 'user created!'
            ]
        ];
    }

}
