<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Models\TeamTask;
use App\Enums\Priority;
use Illuminate\Support\Facades\DB;

class TeamTaskService
{
    public function getTasksByTeamId(int $teamId): array
    {
        $teamMemberIds = TeamMember::where('team_id', $teamId)->pluck('id');
        
        $tasks = TeamTask::whereIn('member_id', $teamMemberIds)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->all();
    }

    public function getTasksByUserId(int $userId): array
    {
        $teamMemberIds = TeamMember::where('user_id', $userId)->pluck('id');
        
        $tasks = TeamTask::whereIn('member_id', $teamMemberIds)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->all();
    }

    public function getTasksByMemberId(int $memberId): array
    {
        $tasks = TeamTask::where('member_id', $memberId)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->all();
    }

    public function getTaskById(int $taskId): ?TeamTask
    {
        return TeamTask::with(['teamMember.user', 'teamMember.team'])->find($taskId);
    }

    public function createTask(array $data, int $teamId, int $userId, string $createdBy): TeamTask
    {
        $memberId = (int) $data['member_id'];
        
        $teamMember = TeamMember::where('id', $memberId)
            ->where('team_id', $teamId)
            ->firstOrFail();

        return TeamTask::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'],
            'priority' => $data['priority'] ? Priority::from($data['priority']) : Priority::MEDIUM,
            'is_completed' => false,
            'member_id' => $memberId,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
        ]);
    }

    public function updateTask(int $taskId, array $data, string $updatedBy): TeamTask
    {
        $task = TeamTask::findOrFail($taskId);

        if (isset($data['assignedUserId'])) {
            $teamMember = TeamMember::where('team_id', $task->teamMember->team_id)
                ->where('user_id', $data['assignedUserId'])
                ->firstOrFail();
            
            $data['member_id'] = $teamMember->id;
            unset($data['assignedUserId']);
        }

        $task->update(array_merge($data, [
            'updated_by' => $updatedBy,
        ]));

        return $task->load(['teamMember.user', 'teamMember.team']);
    }

    public function deleteTask(int $taskId): bool
    {
        $task = TeamTask::findOrFail($taskId);
        $task->delete();
        return true;
    }

    public function toggleTaskCompletion(int $taskId, string $updatedBy): TeamTask
    {
        $task = TeamTask::findOrFail($taskId);
        $task->update([
            'is_completed' => !$task->is_completed,
            'updated_by' => $updatedBy,
        ]);

        return $task->load(['teamMember.user', 'teamMember.team']);
    }
}

