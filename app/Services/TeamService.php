<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    public function getTeamsByUserId(int $userId): array
    {
        $teams = Team::whereHas('teamMembers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['teamMembers.user'])
        ->get();

        return $teams->toArray();
    }

    public function getTeamDetail(int $teamId): ?Team
    {
        return Team::with(['teamMembers.user'])->find($teamId);
    }

    public function createTeam(array $data, int $userId, string $createdBy): Team
    {
        return DB::transaction(function () use ($data, $userId, $createdBy) {
            $team = Team::create([
                'name' => $data['name'],
                'code' => null,
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);

            $team->code = "TODOLIST-{$team->id}";
            $team->save();

            TeamMember::create([
                'team_id' => $team->id,
                'user_id' => $userId,
                'role' => 'LEADER',
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);

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

    public function updateTeam(int $teamId, array $data, string $updatedBy): Team
    {
        $team = Team::findOrFail($teamId);
        $team->update([
            'name' => $data['name'] ?? $team->name,
            'updated_by' => $updatedBy,
        ]);

        return $team->load('teamMembers.user');
    }

    public function deleteTeam(int $teamId): bool
    {
        return DB::transaction(function () use ($teamId) {
            $team = Team::findOrFail($teamId);
            
            $teamMemberIds = $team->teamMembers->pluck('id');
            \App\Models\TeamTask::whereIn('member_id', $teamMemberIds)->delete();
            
            $team->delete();
            
            return true;
        });
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
    
    public function searchUsersByEmailPrefix(string $prefix, int $limit = 10): array
    {
        return User::where('email', 'like', $prefix . '%')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}

