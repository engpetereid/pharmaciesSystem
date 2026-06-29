<?php

namespace App\Repositories;

use App\Models\User;
use App\DTOs\SaveUserDTO;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function store(SaveUserDTO $dto): User;

    public function update(User $user, SaveUserDTO $dto): User;

    public function delete(User $user): bool;

    public function paginate();

    public function findById(int $id): User;

    public function all(): Collection;

}
