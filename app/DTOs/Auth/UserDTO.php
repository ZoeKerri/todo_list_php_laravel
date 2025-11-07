<?php

namespace App\DTOs\Auth;

use App\Models\User;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        public string $name,
        public ?string $avatar = null,
        public ?string $phone = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
        ];
    }

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            email: $user->email,
            name: $user->full_name,
            avatar: $user->avatar,
            phone: $user->phone
        );
    }
}
