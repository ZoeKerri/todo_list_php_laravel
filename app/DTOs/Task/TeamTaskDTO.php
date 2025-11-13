<?php

namespace App\DTOs\Task;

use App\Models\TeamTask;
use App\DTOs\AuditDTO;
use App\Enums\Priority;
use Carbon\Carbon;

class TeamTaskDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public Carbon $deadline,
        public string $priority, // LOW, MEDIUM, HIGH
        public bool $isCompleted,
        public int $memberId,
        public ?int $teamId = null, // Optional: team ID through member
        public ?int $userId = null, // Optional: user ID through member
        public ?AuditDTO $created = null,
        public ?AuditDTO $updated = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline->toISOString(),
            'priority' => $this->priority,
            'isCompleted' => $this->isCompleted,
            'memberId' => $this->memberId,
            'teamId' => $this->teamId,
            'userId' => $this->userId,
            'teamMemberId' => $this->memberId, // Alias for Flutter compatibility
            'created' => $this->created?->toArray(),
            'updated' => $this->updated?->toArray(),
        ];
    }

    public static function fromModel(TeamTask $task, bool $includeRelations = false): self
    {
        $teamId = null;
        $userId = null;

        // Always try to get teamId and userId from relationships if available
        if ($task->relationLoaded('teamMember') && $task->teamMember) {
            $teamId = $task->teamMember->team_id;
            $userId = $task->teamMember->user_id;
        } elseif ($task->teamMember) {
            // If relationship is not loaded but exists, load it
            $task->load('teamMember');
            if ($task->teamMember) {
                $teamId = $task->teamMember->team_id;
                $userId = $task->teamMember->user_id;
            }
        }

        return new self(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            deadline: $task->deadline,
            priority: $task->priority instanceof Priority ? $task->priority->value : (string) $task->priority,
            isCompleted: $task->is_completed,
            memberId: $task->member_id,
            teamId: $teamId,
            userId: $userId,
            created: $task->created_at ? new AuditDTO($task->created_at, $task->created_by) : null,
            updated: $task->updated_at ? new AuditDTO($task->updated_at, $task->updated_by) : null,
        );
    }

    public static function fromCollection($tasks, bool $includeRelations = true): array
    {
        // Handle different input types
        $taskCollection = collect($tasks);
        
        return $taskCollection->map(function ($task) use ($includeRelations) {
            // If it's already a TeamTask model
            if ($task instanceof TeamTask) {
                // Ensure relationships are loaded if needed
                if ($includeRelations) {
                    if (!$task->relationLoaded('teamMember')) {
                        $task->load('teamMember');
                    }
                    if ($task->teamMember && !$task->teamMember->relationLoaded('user')) {
                        $task->teamMember->load('user');
                    }
                    if ($task->teamMember && !$task->teamMember->relationLoaded('team')) {
                        $task->teamMember->load('team');
                    }
                }
                return self::fromModel($task, $includeRelations)->toArray();
            }
            
            // If it's an array (from toArray()), convert back to model
            if (is_array($task) && isset($task['id'])) {
                $taskModel = TeamTask::with($includeRelations ? ['teamMember.user', 'teamMember.team'] : [])
                    ->find($task['id']);
                return $taskModel ? self::fromModel($taskModel, $includeRelations)->toArray() : null;
            }
            
            return null;
        })->filter()->values()->toArray();
    }
}
