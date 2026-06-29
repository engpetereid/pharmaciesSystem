<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SavePharmaDTO;
use App\DTOs\SaveUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Models\User;
use App\Services\Admin\Implementation\UserService;
use App\Services\Admin\IUserService;

class ApiUsersController extends Controller
{
    public function __construct(
        protected IUserService $userService,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=$this->userService->all();
        return response()->json([
            'status'=>true,
            'data'=>$users,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        //
        $dto = SaveUserDTO::fromRequest($request);
        $user = $this->userService->store($dto);
        return response()->json([
            'status' => true,
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user=$this->userService->findById($id);
        return response()->json([
            'status' => true,
            'data' => $user,
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EditUserRequest $request, User $user )
    {
        //
        $dto = SaveUserDTO::fromRequest($request);
        $user = $this->userService->update($user,$dto);
        return response()->json([
            'status' => true,
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return response()->json([
            'status' => true,
            'data' => 'user deleted successfully',
        ], 200);
    }
}
