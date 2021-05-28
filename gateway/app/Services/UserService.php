<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use ConsumeExternalService;

    /**
     * The base uri to consume users service
     * @var string
     */
    public $baseUri;

    /**
     * Authorization secret to pass to author api
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->baseUri =  env('USERS_SERVICE_BASE_URL');
        $this->secret = env('USERS_SERVICE_SECRET');
    }


    /**
     * Login user
     */
    public function login($request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        return $this->performRequest('POST', '/api/v1/user/login' , $data);
    }

    /**
     * User Registeration
     */
    public function register($request)
    {
        $data = [
            'user_type' => $request->input('user_type'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(100),
        ];
        return $this->performRequest('POST', '/api/v1/user/register', $data);
    }
}
