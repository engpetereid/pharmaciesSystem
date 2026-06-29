<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Services\Admin\Implementation\UserService;


class RegisterController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(CreateUserRequest $request)
    {
        $dto = SaveUserDTO::fromRequest($request);
        $user  = $this->userService->store($dto);
        $token = $user->createToken(config('app.name'))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();

        if (! auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user  = auth()->user();
        $token = $user->createToken(config('app.name'))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'data'    => [
                'token' => $token,
            ],
        ], 200);
    }
}
