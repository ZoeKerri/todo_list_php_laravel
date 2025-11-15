<h2>Task Reminder</h2>

<p><b>Task:</b> {{ $task->title }}</p>

@if ($type === 'personal')
    <p><b>Due date:</b> {{ $task->due_date->format('d/m/Y H:i') }}</p>
    <p>This is your personal task.</p>
@else
    <p><b>Deadline:</b> {{ $task->deadline->format('d/m/Y H:i') }}</p>
    <p>This is a team task assigned to you.</p>
@endif

<p>The task is now due and not completed.</p>
<p>Please check your dashboard to complete it.</p>
