<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('authentication.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Generate JWT token for API calls
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            
            // Store token in session for API calls
            session(['jwt_token' => $token]);
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('authentication.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_by' => $request->email,
            'updated_by' => $request->email,
        ]);

        Auth::login($user);
        
        // Generate JWT token for API calls
        $token = JWTAuth::fromUser($user);
        session(['jwt_token' => $token]);

        return redirect('/');
    }

    /**
     * Show OTP verification form
     */
    public function showOtp()
    {
        return view('authentication.otp');
    }

    /**
     * Handle OTP verification
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:6',
            'otp.*' => 'required|string|size:1|numeric',
        ]);

        // Combine OTP array into string
        $otpCode = implode('', $request->otp);

        // For now, accept any 6-digit OTP
        // In production, implement proper OTP verification
        if (strlen($otpCode) === 6 && is_numeric($otpCode)) {
            return redirect('/');
        }

        return back()->withErrors([
            'otp' => 'Invalid OTP code.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear JWT token
        session()->forget('jwt_token');

        return redirect('/login');
    }

    /**
     * Get JWT token for API calls
     */
    public function getApiToken()
    {
        $token = session('jwt_token');
        
        if (!$token) {
            return response()->json(['error' => 'No token available'], 401);
        }

        return response()->json(['token' => $token]);
    }
}
