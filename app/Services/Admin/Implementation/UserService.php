<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveUserDTO;
use App\Models\User;
use App\Repositories\IUserRepository;
use App\Services\Admin\IUserService;
use Illuminate\Pagination\LengthAwarePaginator;


class UserService implements IUserService
{
    public function __construct(
        protected IUserRepository $userRepository
    ) {}
    public function store(SaveUserDTO $dto): User
    {
        return $this->userRepository->store($dto);
    }

    public function update(User $user , SaveUserDTO $dto): User
    {
        return $this->userRepository->update($user, $dto);
    }

    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user);
    }
    public function findById(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->userRepository->paginate();
    }

    public function all()
    {
        return $this->userRepository->all();
    }


}
