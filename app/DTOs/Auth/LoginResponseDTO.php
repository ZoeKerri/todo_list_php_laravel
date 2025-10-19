<?php

namespace App\DTOs\Auth;

class LoginResponseDTO
{
    public function __construct(
        public string $token,
        public UserDTO $user
    ) {}

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'user' => $this->user->toArray(),
        ];
    }
}
