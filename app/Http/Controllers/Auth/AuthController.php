<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Responses\Response;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    //api for register process
    public function register(UserRegisterRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->register($request->validated());
            return Response::Success($data['user'],$data['message']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for login process
    public function login(UserLoginRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->login($request->validated());
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for logout process
    public function logout() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->logout();
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }


}
