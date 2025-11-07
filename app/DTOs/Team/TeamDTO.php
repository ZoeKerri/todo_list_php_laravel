<?php

namespace App\DTOs\Team;

use App\Models\Team;
use App\DTOs\AuditDTO;

class TeamDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code, // QR code: TODOLIST-{id}
        public array $teamMembers,
        public ?AuditDTO $created = null,
        public ?AuditDTO $updated = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'teamMembers' => $this->teamMembers,
            'created' => $this->created?->toArray(),
            'updated' => $this->updated?->toArray(),
        ];
    }

    public static function fromModel(Team $team): self
    {
        $teamMembers = $team->teamMembers->map(function ($member) {
            return TeamMemberDTO::fromModel($member);
        })->toArray();

        return new self(
            id: $team->id,
            name: $team->name,
            code: $team->code, // Accessor from model
            teamMembers: $teamMembers,
            created: $team->created_at ? new AuditDTO($team->created_at, $team->created_by) : null,
            updated: $team->updated_at ? new AuditDTO($team->updated_at, $team->updated_by) : null,
        );
    }
}

