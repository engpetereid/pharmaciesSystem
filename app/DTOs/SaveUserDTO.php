<?php
namespace App\DTOs;


use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRequest;

class SaveUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {}

    public static function fromRequest(
        CreateUserRequest|EditUserRequest $request
    ): self {
        return new self(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            role: $request->role,
        );
    }
}
