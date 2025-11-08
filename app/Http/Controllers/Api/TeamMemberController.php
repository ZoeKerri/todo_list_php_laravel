<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DTOs\ApiResponse;
use App\DTOs\Team\TeamMemberDTO;
use App\Services\TeamMemberService;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{
    protected $teamMemberService;

    public function __construct(TeamMemberService $teamMemberService)
    {
        $this->middleware('auth:api');
        $this->teamMemberService = $teamMemberService;
    }

    public function index(int $teamId)
    {
        $members = $this->teamMemberService->getMembersByTeamId($teamId);
        return ApiResponse::success(TeamMemberDTO::fromCollection($members), 'Members retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
            'role' => ['nullable', 'in:LEADER,MEMBER'],
        ]);

        if (!$this->teamMemberService->isUserLeaderOfTeam(Auth::id(), $validated['team_id'])) {
            return ApiResponse::forbidden('Only the team leader can add members.');
        }

        try {
            $member = $this->teamMemberService->addTeamMember($validated['team_id'], $validated['user_id'], Auth::user()->email, $validated['role'] ?? 'MEMBER');
            return ApiResponse::success(TeamMemberDTO::fromModel($member, true)->toArray(), 'Member added successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:team_members,id',
            'role' => ['required', 'in:LEADER,MEMBER'],
        ]);

        $member = $this->teamMemberService->getMemberById($validated['id']);
        if (!$this->teamMemberService->isUserLeaderOfTeam(Auth::id(), $member->team_id)) {
            return ApiResponse::forbidden('Only the team leader can change roles.');
        }

        try {
            if ($validated['role'] === 'LEADER') {
                $this->teamMemberService->changeLeader($member->team_id, $member->user_id, Auth::user()->email);
            } else {
                $this->teamMemberService->updateMemberRole($member->id, $validated['role'], Auth::user()->email);
            }
            return ApiResponse::success(null, 'Member role updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function destroy(int $memberId)
    {
        $member = $this->teamMemberService->getMemberById($memberId);
        if (!$member) {
            return ApiResponse::notFound('Member not found.');
        }

        if (Auth::id() !== $member->user_id && !$this->teamMemberService->isUserLeaderOfTeam(Auth::id(), $member->team_id)) {
            return ApiResponse::forbidden('You do not have permission to remove this member.');
        }

        if ($member->role === 'LEADER' && Auth::id() === $member->user_id) {
            return ApiResponse::error('Leaders cannot remove themselves. Transfer leadership first.');
        }

        try {
            $this->teamMemberService->deleteMember($member->team_id, $member->user_id);
            return ApiResponse::success(null, 'Member removed successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function deleteMemberAndTasks(int $teamId, int $userId)
    {
        // This logic can be complex. For now, just remove the member.
        // The service layer should handle cascading deletes of tasks.
        return $this->destroy($this->teamMemberService->getMemberByTeamIdAndUserId($teamId, $userId)->id);
    }
}
