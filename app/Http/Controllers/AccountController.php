<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\UserDTO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        // For testing, create a mock user if not authenticated
        $user = Auth::user();
        if (!$user) {
            $user = new \App\Models\User([
                'id' => 1,
                'email' => 'test@example.com',
                'full_name' => 'Test User',
                'phone' => '0123456789',
                'avatar' => null
            ]);
        }
        return view('account.account_info', compact('user'));
    }

    public function edit()
    {
        // For testing, create a mock user if not authenticated
        $user = Auth::user();
        if (!$user) {
            $user = new \App\Models\User([
                'id' => 1,
                'email' => 'test@example.com',
                'full_name' => 'Test User',
                'phone' => '0123456789',
                'avatar' => null
            ]);
        }
        return view('account.change_info', compact('user'));
    }

    public function changePassword()
    {
        return view('account.change_password');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);

        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'phone' => $request->phone ?? $user->phone,
            'updated_by' => $user->email,
        ]);

        return redirect('/account-info')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'updated_by' => $user->email,
        ]);

        return redirect('/account-info')->with('success', 'Password changed successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            $user->update([
                'avatar' => $avatarPath,
                'updated_by' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Upload failed']);
    }
}
