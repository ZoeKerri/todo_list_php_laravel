@props([
    'task' => null,
    'canEdit' => false,
    'isLeader' => false,
    'assignedMember' => null,
    'onToggle' => 'toggleTaskComplete',
    'onClick' => 'viewTaskDetail'
])

@if($task)
<div class="team-task-card {{ $task['isCompleted'] ? 'completed' : 'priority-' . strtolower($task['priority']) }}" 
     onclick="{{ $canEdit ? 'showToggleTaskDialog(' . $task['id'] . ', ' . ($task['isCompleted'] ? 'false' : 'true') . ')' : 'viewTaskDetail(' . $task['id'] . ')' }}">
    <div class="task-card-left">
        <i class="{{ $task['isCompleted'] ? 'fas fa-check-circle' : 'far fa-circle' }} task-checkbox {{ $task['isCompleted'] ? 'completed' : '' }}"></i>
        <div class="task-content">
            <h4 class="task-title {{ $task['isCompleted'] ? 'completed' : '' }}">{{ $task['title'] }}</h4>
            <div class="task-meta">
                @if($assignedMember)
                <span class="task-assignee">
                    <i class="fas fa-user"></i>
                    {{ $assignedMember['name'] ?? $assignedMember['email'] ?? 'Unknown' }}
                </span>
                @endif
                <span class="task-date">
                    <i class="fas fa-clock"></i>
                    {{ \Carbon\Carbon::parse($task['deadline'])->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>
    <i class="fas fa-chevron-right task-arrow" onclick="event.stopPropagation(); viewTaskDetail({{ $task['id'] }})"></i>
</div>
@endif

