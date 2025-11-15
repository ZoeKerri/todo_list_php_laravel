<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamTask;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamMemberService
{
    public function getMembersByTeamId(int $teamId): array
    {
        $members = TeamMember::where('team_id', $teamId)
            ->with('user')
            ->get();

        return $members->all();
    }

    public function getMemberById(int $memberId): ?TeamMember
    {
        return TeamMember::with(['user', 'team'])->find($memberId);
    }

    public function getMembersByUserId(int $userId): array
    {
        $members = TeamMember::where('user_id', $userId)
            ->with(['team', 'user'])
            ->get();

        return $members->toArray();
    }

    public function getMemberByTeamIdAndUserId(int $teamId, int $userId): ?TeamMember
    {
        return TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->with('user')
            ->first();
    }

    public function isUserMemberOfTeam(int $userId, int $teamId): bool
    {
        return TeamMember::where('user_id', $userId)
            ->where('team_id', $teamId)
            ->exists();
    }

    public function isUserLeaderOfTeam(int $userId, int $teamId): bool
    {
        return TeamMember::where('user_id', $userId)
            ->where('team_id', $teamId)
            ->where('role', 'LEADER')
            ->exists();
    }

    public function addTeamMember(int $teamId, int $userId, string $createdBy, string $role = 'MEMBER'): TeamMember
    {
        $existingMember = TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->first();

        if ($existingMember) {
            throw new \Exception('User is already a member of this team');
        }

        return TeamMember::create([
            'team_id' => $teamId,
            'user_id' => $userId,
            'role' => $role,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
        ]);
    }

    public function updateMemberRole(int $memberId, string $role, string $updatedBy): TeamMember
    {
        if (!in_array($role, ['LEADER', 'MEMBER'])) {
            throw new \Exception('Invalid role. Must be LEADER or MEMBER');
        }

        $member = TeamMember::findOrFail($memberId);
        $member->update([
            'role' => $role,
            'updated_by' => $updatedBy,
        ]);

        return $member;
    }

    public function deleteMember(int $teamId, int $userId): bool
    {
        return DB::transaction(function () use ($teamId, $userId) {
            $member = TeamMember::where('team_id', $teamId)
                ->where('user_id', $userId)
                ->firstOrFail();

            TeamTask::where('member_id', $member->id)->delete();

            $member->delete();

            return true;
        });
    }

    public function deleteMemberById(int $memberId): bool
    {
        return DB::transaction(function () use ($memberId) {
            $member = TeamMember::findOrFail($memberId);

            TeamTask::where('member_id', $member->id)->delete();

            $member->delete();

            return true;
        });
    }

    public function changeLeader(int $teamId, int $newLeaderUserId, string $updatedBy): bool
    {
        return DB::transaction(function () use ($teamId, $newLeaderUserId, $updatedBy) {
            TeamMember::where('team_id', $teamId)
                ->where('role', 'LEADER')
                ->update([
                    'role' => 'MEMBER',
                    'updated_by' => $updatedBy,
                ]);

            TeamMember::where('team_id', $teamId)
                ->where('user_id', $newLeaderUserId)
                ->update([
                    'role' => 'LEADER',
                    'updated_by' => $updatedBy,
                ]);

            return true;
        });
    }

    public function getLeader(int $teamId): ?TeamMember
    {
        return TeamMember::where('team_id', $teamId)
            ->where('role', 'LEADER')
            ->with('user')
            ->first();
    }
}

