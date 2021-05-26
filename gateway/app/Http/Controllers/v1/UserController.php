<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\DbErrorException;
use App\Exceptions\LoginErrorException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;


class UserController extends Controller
{

    use ApiResponser;

     /**
     * The service to consume the user micro-service
     * @var UserService
     */
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {

        return $this->successResponse($this->userService->login($request));
    }

    public function register(Request $request)
    {

        return $this->successResponse($this->userService->register($request));

    }

}
