<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Task\TeamTaskDTO;
use App\Http\Controllers\Controller;
use App\Services\TeamTaskService;
use App\Services\TeamMemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamTaskController extends Controller
{
    protected $teamTaskService;
    protected $teamMemberService;

    public function __construct(TeamTaskService $teamTaskService, TeamMemberService $teamMemberService)
    {
        $this->middleware('auth:api');
        $this->teamTaskService = $teamTaskService;
        $this->teamMemberService = $teamMemberService;
    }

    public function index(int $teamId)
    {
        if (!$this->teamMemberService->isUserMemberOfTeam(Auth::id(), $teamId)) {
            return ApiResponse::forbidden('You are not a member of this team.');
        }
        $tasks = $this->teamTaskService->getTasksByTeamId($teamId);
        return ApiResponse::success(TeamTaskDTO::fromCollection($tasks), 'Tasks retrieved successfully');
    }

    public function getTasksForUser(int $userId)
    {
        $tasks = $this->teamTaskService->getTasksByUserId($userId);
        return ApiResponse::success(TeamTaskDTO::fromCollection($tasks), 'Tasks for user retrieved successfully');
    }

    public function getTasksByMember(int $memberId)
    {
        if (!$this->teamMemberService->isUserMemberOfTeam(Auth::id(), $this->teamMemberService->getMemberById($memberId)->team_id)) {
            return ApiResponse::forbidden('You are not a member of this team.');
        }
        $tasks = $this->teamTaskService->getTasksByMemberId($memberId);
        return ApiResponse::success(TeamTaskDTO::fromCollection($tasks), 'Tasks for member retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|integer|exists:teams,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:LOW,MEDIUM,HIGH',
            'member_id' => 'required|integer|exists:team_members,id',
        ]);

        $teamId = (int) $validated['team_id'];
        $userId = Auth::id();
        
        if (!$this->teamMemberService->isUserMemberOfTeam($userId, $teamId)) {
            return ApiResponse::forbidden('You are not a member of this team.');
        }

        try {
            $task = $this->teamTaskService->createTask($validated, $teamId, $userId, Auth::user()->email);
            return ApiResponse::success(TeamTaskDTO::fromModel($task), 'Task created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:team_tasks,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'sometimes|date',
            'priority' => 'sometimes|in:LOW,MEDIUM,HIGH',
            'is_completed' => 'sometimes|boolean',
            'member_id' => 'sometimes|exists:team_members,id',
        ]);

        $task = $this->teamTaskService->getTaskById($validated['id']);
        if (!$task || !$this->teamMemberService->isUserMemberOfTeam(Auth::id(), $task->teamMember->team_id)) {
            return ApiResponse::forbidden('You do not have permission to update this task.');
        }

        try {
            $updatedTask = $this->teamTaskService->updateTask($validated['id'], $validated, Auth::user()->email);
            return ApiResponse::success(TeamTaskDTO::fromModel($updatedTask), 'Task updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function destroy(int $taskId)
    {
        $task = $this->teamTaskService->getTaskById($taskId);
        if (!$task || !$this->teamMemberService->isUserMemberOfTeam(Auth::id(), $task->teamMember->team_id)) {
            return ApiResponse::forbidden('You do not have permission to delete this task.');
        }

        try {
            $this->teamTaskService->deleteTask($taskId);
            return ApiResponse::success(null, 'Task deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}

