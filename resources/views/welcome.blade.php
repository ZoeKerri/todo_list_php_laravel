@extends('layouts.app')

@section('title', 'Your Tasks')

@push('styles')
<style>
    .tasks-content {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    .tasks-content h2, .tasks-content h4 {
        color: var(--text-primary);
        transition: color 0.3s ease;
    }
    
    .tasks-content p {
        color: var(--text-secondary);
        transition: color 0.3s ease;
    }
    
    .calendar {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        transition: background-color 0.3s ease;
    }
    
    .calendar div {
        color: var(--text-primary);
        transition: color 0.3s ease;
    }
    
    .scrollable-buttons {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        margin: 20px 0;
        transition: background-color 0.3s ease;
    }
</style>
@endpush

@section('content')

    <div id="tasks-content" class="tasks-content">
        <h2>Hi there,</h2>
        <h4>Your Task</h4>
        <div id="buttonContainer" class="scrollable-buttons" onmousedown="startDragging(event)"
            onmouseup="stopDragging()" onmouseleave="stopDragging()" onmousemove="drag(event)">
            
            <button type="button" class="btn btn-purple">Personal</button>
            <button type="button" class="btn btn-purple">Work</button>
            <button type="button" class="btn btn-purple">Health</button>
            <button type="button" class="btn btn-purple">Study</button>
            </div>
        <div class="calendar">
            <div>Sun <span>8</span> Jun</div>
            <div>Mon <span>9</span> Jun</div>
            <div>Tue <span>10</span> Jun</div>
            <div>Wed <span>11</span> Jun</div>
        </div>
        <p>0 Tasks For 08/06/2025</p>
        <p>No tasks yet</p>
        <p>Add your first task to get started</p>
        <button class="btn btn-purple">Add Task</button>
    </div>

@endsection