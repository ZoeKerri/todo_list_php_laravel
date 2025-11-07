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

    /**
     * Get teams by user ID.
     * GET /api/v1/team/{userId}
     */
    public function getTeamsByUserId(int $userId): JsonResponse
    {
        $currentUser = Auth::user();
        
        // Users can only view their own teams
        if ($currentUser->id != $userId) {
            return ApiResponse::forbidden('You can only view your own teams');
        }

        try {
            $teams = $this->teamService->getTeamsByUserId($userId);
            $teamDTOs = collect($teams)->map(function ($team) {
                return TeamDTO::fromModel(Team::find($team['id']));
            })->toArray();

            return ApiResponse::success($teamDTOs, 'Get teams successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to get teams: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get team detail by ID.
     * GET /api/v1/team/detail/{teamId}
     */
    public function show(int $teamId): JsonResponse
    {
        $currentUser = Auth::user();
        
        // Check if user is a member of this team
        $member = $this->teamMemberService->getMemberByTeamIdAndUserId($teamId, $currentUser->id);
        if (!$member) {
            return ApiResponse::forbidden('You are not a member of this team');
        }

        try {
            $team = $this->teamService->getTeamDetail($teamId);
            if (!$team) {
                return ApiResponse::notFound('Team not found');
            }

            $teamDTO = TeamDTO::fromModel($team);
            return ApiResponse::success($teamDTO->toArray(), 'Get team detail successful');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to get team detail: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Create a new team.
     * POST /api/v1/team/{userId}
     */
    public function store(Request $request, int $userId): JsonResponse
    {
        $currentUser = Auth::user();
        
        // Users can only create teams for themselves
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

    /**
     * Update team.
     * PUT /api/v1/team
     */
    public function update(Request $request): JsonResponse
    {
        $currentUser = Auth::user();
        
        $request->validate([
            'id' => 'required|integer|exists:teams,id',
            'name' => 'required|string|max:255',
        ]);

        $teamId = $request->input('id');
        
        // Check if user is leader of this team
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

    /**
     * Delete team (disband).
     * DELETE /api/v1/team/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $currentUser = Auth::user();
        
        // Check if user is leader of this team
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

    /**
     * Delete team with all tasks.
     * DELETE /api/v1/team/task/{id}
     */

    /**
     * Get user by email (for adding members).
     * GET /api/v1/{email}
     */
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
}
