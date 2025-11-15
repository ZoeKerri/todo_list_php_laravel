<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Team\TeamDTO;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\TeamService;
use App\Services\TeamMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    protected TeamService $teamService;
    protected TeamMemberService $teamMemberService;

    public function __construct(TeamService $teamService, TeamMemberService $teamMemberService)
    {
        $this->middleware('auth:api');
        $this->teamService = $teamService;
        $this->teamMemberService = $teamMemberService;
    }

    public function getTeamsByUserId(int $userId): JsonResponse
    {
        $currentUser = Auth::user();
        
        if ($currentUser->id != $userId) {
            return ApiResponse::forbidden('You can only view your own teams');
        }

        try {
            $teams = $this->teamService->getTeamsByUserId($userId);
            $teamModels = collect($teams)->map(function ($teamData) {
                return Team::with(['teamMembers.user'])->find($teamData['id']);
            })->filter();
            
            $teamDTOs = $teamModels->map(function ($team) {
                return TeamDTO::fromModel($team, true);
            })->toArray();

            return ApiResponse::success($teamDTOs, 'Get teams successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to get teams: ' . $e->getMessage(), null, 500);
        }
    }

    public function show(int $teamId): JsonResponse
    {
        $currentUser = Auth::user();
        
        $member = $this->teamMemberService->getMemberByTeamIdAndUserId($teamId, $currentUser->id);
        if (!$member) {
            return ApiResponse::forbidden('You are not a member of this team');
        }

        try {
            $team = $this->teamService->getTeamDetail($teamId);
            if (!$team) {
                return ApiResponse::notFound('Team not found');
            }

            $teamDTO = TeamDTO::fromModel($team, true);
            return ApiResponse::success($teamDTO->toArray(), 'Get team detail successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to get team detail: ' . $e->getMessage(), null, 500);
        }
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $currentUser = Auth::user();
        
        if ($currentUser->id != $userId) {
            return ApiResponse::forbidden('You can only create teams for yourself');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'teamMembers' => 'nullable|array',
            'teamMembers.*.userId' => 'required|integer|exists:users,id',
            'teamMembers.*.role' => 'nullable|string|in:LEADER,MEMBER',
        ]);

        try {
            $team = $this->teamService->createTeam(
                $request->all(),
                $userId,
                $currentUser->email
            );

            $teamDTO = TeamDTO::fromModel($team);
            return ApiResponse::success($teamDTO->toArray(), 'Team created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create team: ' . $e->getMessage(), null, 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $currentUser = Auth::user();
        
        $request->validate([
            'id' => 'required|integer|exists:teams,id',
            'name' => 'required|string|max:255',
        ]);

        $teamId = $request->input('id');
        
        $leader = $this->teamMemberService->getLeader($teamId);
        if (!$leader || $leader->user_id != $currentUser->id) {
            return ApiResponse::forbidden('Only team leader can update the team');
        }

        try {
            $team = $this->teamService->updateTeam(
                $teamId,
                $request->all(),
                $currentUser->email
            );

            $teamDTO = TeamDTO::fromModel($team);
            return ApiResponse::success($teamDTO->toArray(), 'Team updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update team: ' . $e->getMessage(), null, 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $currentUser = Auth::user();
        
        $leader = $this->teamMemberService->getLeader($id);
        if (!$leader || $leader->user_id != $currentUser->id) {
            return ApiResponse::forbidden('Only team leader can delete the team');
        }

        try {
            $this->teamService->deleteTeam($id);
            return ApiResponse::success(null, 'Team deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete team: ' . $e->getMessage(), null, 500);
        }
    }

    public function getUserByEmail(string $email): JsonResponse
    {
        $currentUser = Auth::user();

        try {
            $user = $this->teamService->getUserByEmail($email);
            if (!$user) {
                return ApiResponse::notFound('User not found');
            }

            return ApiResponse::success([
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
            ], 'Get user successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to get user: ' . $e->getMessage(), null, 500);
        }
    }

    public function searchUsersByEmailPrefix(Request $request): JsonResponse
    {
        $currentUser = Auth::user();

        $request->validate([
            'prefix' => 'required|string|min:1|max:255',
            'limit' => 'sometimes|integer|min:1|max:20',
        ]);

        try {
            $prefix = $request->input('prefix');
            $limit = $request->input('limit', 10);
            
            $users = $this->teamService->searchUsersByEmailPrefix($prefix, $limit);
            
            $userList = array_map(function ($user) {
                return [
                    'id' => $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'] ?? null,
                    'avatar' => $user['avatar'] ?? null,
                ];
            }, $users);

            return ApiResponse::success($userList, 'Search users successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to search users: ' . $e->getMessage(), null, 500);
        }
    }
}
