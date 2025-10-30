<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Task\TeamTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamTaskRequest;
use App\Models\TeamTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamTaskController extends Controller
{
    /**
     * Create a new TeamTaskController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of team tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'teamId' => 'required|exists:teams,id',
        ]);

        $teamId = $request->teamId;
        
        // Check if user is member of the team
        $isMember = $user->teams()->where('team_id', $teamId)->exists();
        $isOwner = $user->ownedTeams()->where('id', $teamId)->exists();
        
        if (!$isMember && !$isOwner) {
            return ApiResponse::forbidden('You are not a member of this team');
        }

        $query = TeamTask::where('team_id', $teamId);

        // Check cookie for show_completed_tasks setting
        $showCompletedTasks = $request->cookie('user_settings');
        if ($showCompletedTasks) {
            $settings = json_decode($showCompletedTasks, true);
            if (isset($settings['show_completed_tasks']) && !$settings['show_completed_tasks']) {
                // If show_completed_tasks is false, exclude completed tasks
                $query->where('status', '!=', 'completed');
            }
        }

        $tasks = $query->with(['category', 'assignedTo'])
            ->orderBy('due_date', 'asc')
            ->get();

        $taskDTOs = $tasks->map(fn($task) => TeamTaskDTO::fromModel($task));

        return ApiResponse::success($taskDTOs->map(fn($dto) => $dto->toArray()), 'Get team tasks successful');
    }

    /**
     * Store a newly created team task in storage.
     */
    public function store(TeamTaskRequest $request): JsonResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'teamId' => 'required|exists:teams,id',
        ]);

        $teamId = $request->teamId;
        
        // Check if user is member of the team
        $isMember = $user->teams()->where('team_id', $teamId)->exists();
        $isOwner = $user->ownedTeams()->where('id', $teamId)->exists();
        
        if (!$isMember && !$isOwner) {
            return ApiResponse::forbidden('You are not a member of this team');
        }

        $task = TeamTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => $request->status ?? 'pending',
            'team_id' => $teamId,
            'assigned_to' => $request->assigned_to,
            'category_id' => $request->category_id,
            'created_by' => $user->email,
            'updated_by' => $user->email,
        ]);

        $taskDTO = TeamTaskDTO::fromModel($task);

        return ApiResponse::success($taskDTO->toArray(), 'Team task created successfully');
    }

    /**
     * Display the specified team task.
     */
    public function show(TeamTask $teamTask): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is member of the team
        $isMember = $user->teams()->where('team_id', $teamTask->team_id)->exists();
        $isOwner = $user->ownedTeams()->where('id', $teamTask->team_id)->exists();
        
        if (!$isMember && !$isOwner) {
            return ApiResponse::forbidden('You are not authorized to view this task');
        }

        $taskDTO = TeamTaskDTO::fromModel($teamTask);

        return ApiResponse::success($taskDTO->toArray(), 'Get team task successful');
    }

    /**
     * Update the specified team task in storage.
     */
    public function update(TeamTaskRequest $request, TeamTask $teamTask): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is member of the team
        $isMember = $user->teams()->where('team_id', $teamTask->team_id)->exists();
        $isOwner = $user->ownedTeams()->where('id', $teamTask->team_id)->exists();
        
        if (!$isMember && !$isOwner) {
            return ApiResponse::forbidden('You are not authorized to update this task');
        }

        $teamTask->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? $teamTask->priority,
            'status' => $request->status ?? $teamTask->status,
            'assigned_to' => $request->assigned_to,
            'category_id' => $request->category_id,
            'updated_by' => $user->email,
        ]);

        $taskDTO = TeamTaskDTO::fromModel($teamTask->fresh());

        return ApiResponse::success($taskDTO->toArray(), 'Team task updated successfully');
    }

    /**
     * Remove the specified team task from storage.
     */
    public function destroy(TeamTask $teamTask): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is owner of the team
        $isOwner = $user->ownedTeams()->where('id', $teamTask->team_id)->exists();
        
        if (!$isOwner) {
            return ApiResponse::forbidden('Only team owner can delete tasks');
        }

        $teamTask->delete();

        return ApiResponse::success(null, 'Team task deleted successfully');
    }
}
