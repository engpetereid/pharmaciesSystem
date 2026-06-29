<?php
namespace App\Repositories\Implementation;
use App\Models\User;
use App\DTOs\SaveUserDTO;
use App\Repositories\IUserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository{
    public function store(SaveUserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);
    }
    public function update(User $user, SaveUserDTO $dto): User
    {
        $user->update([
            'name'=> $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);
        return $user->refresh();
    }
    public function delete(User $user): bool
    {
        $user->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return User::paginate(25);
    }

    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function all(): Collection
    {
        return User::all();
    }
}
