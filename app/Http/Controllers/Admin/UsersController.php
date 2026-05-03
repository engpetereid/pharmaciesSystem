<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;

class UsersController extends Controller
{
    /**
     * Fix Issue 8: replaced User::get() with paginate(25).
     * Fix Issue 10: removed // stub comments, fixed spacing, added flash messages.
     */
    public function index()
    {
        $users = User::paginate(25);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Fix Issue 10: changed string $id → int $id for semantic correctness.
     * Added flash success message consistent with other controllers.
     */
    public function store(CreateUserRequest $request, UserService $userService)
    {
        $userService->create($request->validated());
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(EditUserRequest $request, int $id, UserService $userService)
    {
        $userService->update($id, $request->validated());
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(int $id, UserService $userService)
    {
        $userService->delete($id);
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
