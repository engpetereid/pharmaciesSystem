<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class ApiUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users=User::all();
        return response()->json([
            'status'=>true,
            'data'=>$users,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request,UserService $userService)
    {
        //
        $user = $userService->create($request->validated());
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
        $user=User::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $user,
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EditUserRequest $request, string $id , UserService $userService)
    {
        //
        $user = $userService->update($id, $request->validated());
        return response()->json([
            'status' => true,
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id , UserService $userService)
    {
        $userService->delete($id);
        return response()->json([
            'status' => true,
            'data' => 'user deleted successfully',
        ], 200);
    }
}
