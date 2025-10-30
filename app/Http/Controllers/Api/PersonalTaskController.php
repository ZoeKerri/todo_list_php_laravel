<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Task\PersonalTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalTaskRequest;
use App\Models\PersonalTask;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalTaskController extends Controller
{
    /**
     * Create a new PersonalTaskController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = PersonalTask::where('user_id', $user->id);

        // Filter by date if provided
        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('due_date', $date);
        }

        // Filter by completed status if provided
        if ($request->has('completed')) {
            $query->where('completed', $request->boolean('completed'));
        }

        // Check cookie for show_completed_tasks setting
        $showCompletedTasks = $request->cookie('user_settings');
        if ($showCompletedTasks) {
            $settings = json_decode($showCompletedTasks, true);
            if (isset($settings['show_completed_tasks']) && !$settings['show_completed_tasks']) {
                // If show_completed_tasks is false, exclude completed tasks
                $query->where('completed', false);
            }
        }

        $tasks = $query->with('category')->orderBy('due_date', 'asc')->get();
        $taskDTOs = $tasks->map(fn($task) => PersonalTaskDTO::fromModel($task));

        return ApiResponse::success($taskDTOs->map(fn($dto) => $dto->toArray()), 'Get personal tasks successful');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalTaskRequest $request): JsonResponse
    {
        $user = Auth::user();
        
        $task = PersonalTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'completed' => $request->completed ?? false,
            'notification_time' => $request->notification_time,
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'created_by' => $user->email,
            'updated_by' => $user->email,
        ]);

        $taskDTO = PersonalTaskDTO::fromModel($task);

        return ApiResponse::success($taskDTO->toArray(), 'Personal task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalTask $personalTask): JsonResponse
    {
        $user = Auth::user();
        
        if ($personalTask->user_id !== $user->id) {
            return ApiResponse::forbidden('You are not authorized to view this task');
        }

        $taskDTO = PersonalTaskDTO::fromModel($personalTask);

        return ApiResponse::success($taskDTO->toArray(), 'Get personal task successful');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonalTaskRequest $request, PersonalTask $personalTask): JsonResponse
    {
        $user = Auth::user();
        
        if ($personalTask->user_id !== $user->id) {
            return ApiResponse::forbidden('You are not authorized to update this task');
        }

        $personalTask->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? $personalTask->priority,
            'completed' => $request->completed ?? $personalTask->completed,
            'notification_time' => $request->notification_time,
            'category_id' => $request->category_id,
            'updated_by' => $user->email,
        ]);

        $taskDTO = PersonalTaskDTO::fromModel($personalTask->fresh());

        return ApiResponse::success($taskDTO->toArray(), 'Personal task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalTask $personalTask): JsonResponse
    {
        $user = Auth::user();
        
        if ($personalTask->user_id !== $user->id) {
            return ApiResponse::forbidden('You are not authorized to delete this task');
        }

        $personalTask->delete();

        return ApiResponse::success(null, 'Personal task deleted successfully');
    }

    /**
     * Get tasks count for a specific day.
     */
    public function getTasksCountForDay(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $count = PersonalTask::where('user_id', $request->userId)
            ->whereDate('due_date', $date)
            ->count();

        return ApiResponse::success($count, 'Total tasks in day');
    }

    /**
     * Get completed tasks count for a specific day.
     */
    public function getCompletedTasksCountForDay(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $count = PersonalTask::where('user_id', $request->userId)
            ->whereDate('due_date', $date)
            ->where('completed', true)
            ->count();

        return ApiResponse::success($count, 'Completed tasks in day');
    }
}
