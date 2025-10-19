<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .task-details {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 5px;
        }
        .priority-high {
            color: #dc3545;
            font-weight: bold;
        }
        .priority-medium {
            color: #ffc107;
            font-weight: bold;
        }
        .priority-low {
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Reminder</h1>
        <p>Hello {{ $user->full_name }},</p>
        <p>This is a reminder for your upcoming task.</p>
    </div>

    <div class="task-details">
        <h2>{{ $task->title }}</h2>
        
        @if($task->description)
            <p><strong>Description:</strong></p>
            <p>{{ $task->description }}</p>
        @endif

        <p><strong>Due Date:</strong> {{ $task->due_date->format('F j, Y \a\t g:i A') }}</p>
        
        @if($task->priority)
            <p><strong>Priority:</strong> 
                <span class="priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </p>
        @endif

        @if($task->category)
            <p><strong>Category:</strong> {{ $task->category->name }}</p>
        @endif

        <p><strong>Status:</strong> 
            @if($task->completed)
                <span style="color: #28a745;">Completed</span>
            @else
                <span style="color: #ffc107;">Pending</span>
            @endif
        </p>
    </div>

    <div class="footer">
        <p>This is an automated reminder from your Todo List application.</p>
        <p>If you have any questions, please contact support.</p>
    </div>
</body>
</html>
