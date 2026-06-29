<?php

namespace App\Services\Admin;

use App\DTOs\SaveUserDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): User;

    public function store(SaveUserDTO $dto): User;

    public function update(User $user, SaveUserDTO $dto): User;

    public function delete(User $user): bool;
}
