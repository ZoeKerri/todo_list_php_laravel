<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamTask;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamMemberService
{
    /**
     * Get team members by team ID.
     */
    public function getMembersByTeamId(int $teamId): array
    {
        $members = TeamMember::where('team_id', $teamId)
            ->with('user')
            ->get();

        return $members->toArray();
    }

    /**
     * Get team members by user ID (all teams user is a member of).
     */
    public function getMembersByUserId(int $userId): array
    {
        $members = TeamMember::where('user_id', $userId)
            ->with(['team', 'user'])
            ->get();

        return $members->toArray();
    }

    /**
     * Get specific team member by team ID and user ID.
     */
    public function getMemberByTeamIdAndUserId(int $teamId, int $userId): ?TeamMember
    {
        return TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->with('user')
            ->first();
    }

    /**
     * Add member to team.
     */
    public function addTeamMember(int $teamId, int $userId, string $createdBy, string $role = 'MEMBER'): TeamMember
    {
        // Check if member already exists
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

    /**
     * Update member role.
     */
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

    /**
     * Delete member from team.
     */
    public function deleteMember(int $teamId, int $userId): bool
    {
        return DB::transaction(function () use ($teamId, $userId) {
            $member = TeamMember::where('team_id', $teamId)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Delete all tasks assigned to this member
            TeamTask::where('member_id', $member->id)->delete();

            // Delete member
            $member->delete();

            return true;
        });
    }

    /**
     * Delete member by member ID (with all tasks).
     */
    public function deleteMemberById(int $memberId): bool
    {
        return DB::transaction(function () use ($memberId) {
            $member = TeamMember::findOrFail($memberId);

            // Delete all tasks assigned to this member
            TeamTask::where('member_id', $member->id)->delete();

            // Delete member
            $member->delete();

            return true;
        });
    }

    /**
     * Change leader of a team.
     */
    public function changeLeader(int $teamId, int $newLeaderUserId, string $updatedBy): bool
    {
        return DB::transaction(function () use ($teamId, $newLeaderUserId, $updatedBy) {
            // Set old leader to MEMBER
            TeamMember::where('team_id', $teamId)
                ->where('role', 'LEADER')
                ->update([
                    'role' => 'MEMBER',
                    'updated_by' => $updatedBy,
                ]);

            // Set new leader
            TeamMember::where('team_id', $teamId)
                ->where('user_id', $newLeaderUserId)
                ->update([
                    'role' => 'LEADER',
                    'updated_by' => $updatedBy,
                ]);

            return true;
        });
    }

    /**
     * Get leader of a team.
     */
    public function getLeader(int $teamId): ?TeamMember
    {
        return TeamMember::where('team_id', $teamId)
            ->where('role', 'LEADER')
            ->with('user')
            ->first();
    }
}

