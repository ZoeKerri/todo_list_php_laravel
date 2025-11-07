<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Models\TeamTask;
use App\Enums\Priority;
use Illuminate\Support\Facades\DB;

class TeamTaskService
{
    /**
     * Get team tasks by team ID.
     */
    public function getTasksByTeamId(int $teamId): array
    {
        $teamMemberIds = TeamMember::where('team_id', $teamId)->pluck('id');
        
        $tasks = TeamTask::whereIn('member_id', $teamMemberIds)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->toArray();
    }

    /**
     * Get team tasks by user ID (all teams user is a member of).
     */
    public function getTasksByUserId(int $userId): array
    {
        $teamMemberIds = TeamMember::where('user_id', $userId)->pluck('id');
        
        $tasks = TeamTask::whereIn('member_id', $teamMemberIds)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->toArray();
    }

    /**
     * Get team tasks by member ID.
     */
    public function getTasksByMemberId(int $memberId): array
    {
        $tasks = TeamTask::where('member_id', $memberId)
            ->with(['teamMember.user', 'teamMember.team'])
            ->get();

        return $tasks->toArray();
    }

    /**
     * Create a new team task.
     */
    public function createTask(array $data, int $teamId, int $userId, string $createdBy): TeamTask
    {
        // Find or create team member
        $teamMember = TeamMember::where('team_id', $teamId)
            ->where('user_id', $data['assignedUserId'] ?? $userId)
            ->firstOrFail();

        return TeamTask::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'],
            'priority' => $data['priority'] ? Priority::from($data['priority']) : Priority::MEDIUM,
            'is_completed' => false,
            'member_id' => $teamMember->id,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
        ]);
    }

    /**
     * Update team task.
     */
    public function updateTask(int $taskId, array $data, string $updatedBy): TeamTask
    {
        $task = TeamTask::findOrFail($taskId);

        // Update member if assigned user changed
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

    /**
     * Delete team task.
     */
    public function deleteTask(int $taskId): bool
    {
        $task = TeamTask::findOrFail($taskId);
        $task->delete();
        return true;
    }

    /**
     * Toggle task completion status.
     */
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

