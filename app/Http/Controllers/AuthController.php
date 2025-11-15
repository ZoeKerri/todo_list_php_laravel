<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpMail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            
            session(['jwt_token' => $token]);
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('authentication.register');
    }

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
        
        $token = JWTAuth::fromUser($user);
        session(['jwt_token' => $token]);

        return redirect('/');
    }

    public function showOtp()
    {
        return view('authentication.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:6',
            'otp.*' => 'required|string|digits:1',
        ]);

        $otpCode = implode('', $request->otp);

        $email = session('reset_email');

        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired. Please request a new OTP.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->otp_code === $otpCode && $user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            return redirect('/reset-password')->with('email', $email);
        }

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP code.',
        ]);
    }

    public function showForgotPassword()
    {
        return view('authentication.forgot_password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found.',
            ]);
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        session(['reset_email' => $email]);

        try {
            Mail::to($email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        return redirect('/otp')->with('success', 'OTP has been sent to your email.');
    }

    public function showResetPassword()
    {
        $email = session('reset_email');
        
        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired. Please request a new OTP.',
            ]);
        }

        return view('authentication.reset_password', [
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $email = session('reset_email');
        
        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired. Please request a new OTP.',
            ]);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($request->email !== $email) {
            return back()->withErrors([
                'email' => 'Invalid email address.',
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->updated_by = $request->email;
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        session()->forget(['reset_email']);

        return redirect('/login')->with('success', 'Password has been reset successfully. Please login with your new password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        session()->forget('jwt_token');

        return redirect('/login');
    }

    public function getApiToken()
    {
        $token = session('jwt_token');
        
        if (!$token) {
            return response()->json(['error' => 'No token available'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function authenticateFromToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $user = JWTAuth::setToken($request->token)->authenticate();
            
            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            session(['jwt_token' => $request->token]);

            return response()->json([
                'success' => true,
                'message' => 'Authenticated successfully',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->full_name,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token verification failed'], 401);
        }
    }
}
