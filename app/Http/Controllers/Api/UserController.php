<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Auth\UserDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get user profile.
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);

        return ApiResponse::success($userDTO->toArray(), 'Get profile successful');
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password|string',
            'password' => 'sometimes|string|min:6|confirmed',
        ]);

        $updateData = [];
        
        if ($request->has('full_name')) {
            $updateData['full_name'] = $request->full_name;
        }
        
        if ($request->has('email')) {
            $updateData['email'] = $request->email;
        }
        
        if ($request->has('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponse::error('Current password is incorrect');
            }
            $updateData['password'] = Hash::make($request->password);
        }

        $updateData['updated_by'] = $user->email;
        
        $user->update($updateData);
        $userDTO = UserDTO::fromModel($user->fresh());

        return ApiResponse::success($userDTO->toArray(), 'Profile updated successfully');
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            $user->update([
                'avatar' => $avatarPath,
                'updated_by' => $user->email,
            ]);

            $userDTO = UserDTO::fromModel($user->fresh());

            return ApiResponse::success($userDTO->toArray(), 'Avatar uploaded successfully');
        }

        return ApiResponse::error('Avatar upload failed');
    }
}
