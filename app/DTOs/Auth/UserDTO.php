<?php

namespace App\DTOs\Auth;

use App\Models\User;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        public string $fullName,
        public ?string $avatar = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullName' => $this->fullName,
            'avatar' => $this->avatar,
        ];
    }

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            email: $user->email,
            fullName: $user->full_name,
            avatar: $user->avatar
        );
    }
}
