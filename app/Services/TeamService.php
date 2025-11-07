<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    /**
     * Get teams by user ID (where user is a member or leader).
     */
    public function getTeamsByUserId(int $userId): array
    {
        $teams = Team::whereHas('teamMembers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['teamMembers.user'])
        ->get();

        return $teams->toArray();
    }

    /**
     * Get team detail by ID.
     */
    public function getTeamDetail(int $teamId): ?Team
    {
        return Team::with(['teamMembers.user'])->find($teamId);
    }

    /**
     * Create a new team with members.
     */
    public function createTeam(array $data, int $userId, string $createdBy): Team
    {
        return DB::transaction(function () use ($data, $userId, $createdBy) {
            // Create team
            $team = new Team([
                'name' => $data['name'],
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);
            $team->save(); // Save to get the ID

            // Generate and save the unique code
            $team->code = "TODOLIST-{$team->id}";
            $team->save();

            // Add creator as leader
            TeamMember::create([
                'team_id' => $team->id,
                'user_id' => $userId,
                'role' => 'LEADER',
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);

            // Add other members if provided
            if (isset($data['teamMembers']) && is_array($data['teamMembers'])) {
                foreach ($data['teamMembers'] as $memberData) {
                    if (isset($memberData['userId']) && $memberData['userId'] != $userId) {
                        TeamMember::create([
                            'team_id' => $team->id,
                            'user_id' => $memberData['userId'],
                            'role' => $memberData['role'] ?? 'MEMBER',
                            'created_by' => $createdBy,
                            'updated_by' => $createdBy,
                        ]);
                    }
                }
            }

            return $team->load('teamMembers.user');
        });
    }

    /**
     * Update team name.
     */
    public function updateTeam(int $teamId, array $data, string $updatedBy): Team
    {
        $team = Team::findOrFail($teamId);
        $team->update([
            'name' => $data['name'] ?? $team->name,
            'updated_by' => $updatedBy,
        ]);

        return $team->load('teamMembers.user');
    }

    /**
     * Delete team (disband).
     */
    public function deleteTeam(int $teamId): bool
    {
        return DB::transaction(function () use ($teamId) {
            $team = Team::findOrFail($teamId);
            
            // Delete all team tasks first
            $teamMemberIds = $team->teamMembers->pluck('id');
            \App\Models\TeamTask::whereIn('member_id', $teamMemberIds)->delete();
            
            // Delete team (cascade will delete team members)
            $team->delete();
            
            return true;
        });
    }

    /**
     * Get user by email (for adding members).
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}

