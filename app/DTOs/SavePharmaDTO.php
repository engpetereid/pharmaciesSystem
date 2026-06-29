<?php
namespace App\DTOs;


use App\Http\Requests\Pharma\CreatePharmaRequest;
use App\Http\Requests\Pharma\EditPharmaRequest;

class SavePharmaDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $user_id,
    ) {}

    public static function fromRequest(
        CreatePharmaRequest|EditPharmaRequest $request
    ): self {
        return new self(
            name: $request->name,
            user_id: $request->user_id,
        );
    }
}
