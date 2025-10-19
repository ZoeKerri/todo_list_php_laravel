<?php

namespace App\DTOs;

use Carbon\Carbon;

class AuditDTO
{
    public function __construct(
        public Carbon $createdAt,
        public ?string $createdBy
    ) {}

    public function toArray(): array
    {
        return [
            'createdAt' => $this->createdAt->toISOString(),
            'createdBy' => $this->createdBy,
        ];
    }
}
