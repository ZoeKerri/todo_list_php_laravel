<?php

namespace App\DTOs\Task;

use App\Models\TeamTask;
use Carbon\Carbon;

class TeamTaskDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public ?Carbon $dueDate,
        public ?string $priority,
        public ?string $status,
        public ?int $assignedTo,
        public ?int $categoryId,
        public ?AuditDTO $created,
        public ?AuditDTO $updated
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'dueDate' => $this->dueDate?->toDateString(),
            'priority' => $this->priority,
            'status' => $this->status,
            'assignedTo' => $this->assignedTo,
            'categoryId' => $this->categoryId,
            'created' => $this->created?->toArray(),
            'updated' => $this->updated?->toArray(),
        ];
    }

    public static function fromModel(TeamTask $task): self
    {
        return new self(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            dueDate: $task->due_date,
            priority: $task->priority,
            status: $task->status,
            assignedTo: $task->assigned_to,
            categoryId: $task->category_id,
            created: $task->created_at ? new AuditDTO($task->created_at, $task->created_by) : null,
            updated: $task->updated_at ? new AuditDTO($task->updated_at, $task->updated_by) : null,
        );
    }
}
