<?php

namespace App\Services\Admin;

use App\Models\User;

/**
 * Fix Issue 9: added PHP return type declarations to all methods.
 */
class UserService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        return (bool) User::findOrFail($id)->delete();
    }
}
