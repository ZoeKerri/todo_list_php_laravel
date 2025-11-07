<?php

namespace App\DTOs\Team;

use App\Models\TeamMember;
use App\DTOs\AuditDTO;

class TeamMemberDTO
{
    public function __construct(
        public int $id,
        public string $role, // LEADER or MEMBER
        public int $userId,
        public int $teamId,
        public ?array $user = null, // User data if loaded
        public ?AuditDTO $created = null,
        public ?AuditDTO $updated = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'userId' => $this->userId,
            'teamId' => $this->teamId,
            'user' => $this->user,
            'created' => $this->created?->toArray(),
            'updated' => $this->updated?->toArray(),
        ];
    }

    public static function fromModel(TeamMember $member, bool $includeUser = false): self
    {
        $userData = null;
        if ($includeUser && $member->relationLoaded('user') && $member->user) {
            $userData = [
                'id' => $member->user->id,
                'name' => $member->user->full_name,
                'email' => $member->user->email,
                'phone' => $member->user->phone,
                'avatar' => $member->user->avatar,
            ];
        }

        return new self(
            id: $member->id,
            role: $member->role instanceof \App\Enums\Role ? $member->role->value : (string)$member->role,
            userId: $member->user_id,
            teamId: $member->team_id,
            user: $userData,
            created: $member->created_at ? new AuditDTO($member->created_at, $member->created_by) : null,
            updated: $member->updated_at ? new AuditDTO($member->updated_at, $member->updated_by) : null,
        );
    }
}

