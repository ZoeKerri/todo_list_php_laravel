<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Auth\LoginResponseDTO;
use App\DTOs\Auth\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use Illuminate\Http\Request;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'verifyOtp', 'resetPassword', 'resendCode', 'loginGoogle']]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::error('Invalid credentials', null, 401);
        }

        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);
        $loginResponse = new LoginResponseDTO($token, $userDTO);

        return ApiResponse::success($loginResponse->toArray(), 'Login successful');
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'created_by' => $request->email,
            'updated_by' => $request->email,
        ]);

        $userDTO = UserDTO::fromModel($user);

        return ApiResponse::success($userDTO->toArray(), 'Register successful');
    }

    public function profile(): JsonResponse
    {
        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);

        return ApiResponse::success($userDTO->toArray(), 'Get profile successful');
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return ApiResponse::success(null, 'Successfully logged out');
    }

    public function refresh(): JsonResponse
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);
        $loginResponse = new LoginResponseDTO($newToken, $userDTO);

        return ApiResponse::success($loginResponse->toArray(), 'Token refreshed successfully');
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->query('email'))->first();

        if (!$user) {
            return ApiResponse::error('User not found', null, 404);
        }

        $otp = rand(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        return ApiResponse::success(UserDTO::fromModel($user)->toArray(), 'OTP sent successfully');
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->otp || $user->otp_expires_at < Carbon::now() || !Hash::check($request->code, $user->otp)) {
            return ApiResponse::error('Invalid or expired OTP', null, 400);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = JWTAuth::fromUser($user);
        $userDTO = UserDTO::fromModel($user);
        $loginResponse = new LoginResponseDTO($token, $userDTO);

        return ApiResponse::success($loginResponse->toArray(), 'OTP verified successfully');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->otp || $user->otp_expires_at < Carbon::now() || !Hash::check($request->code, $user->otp)) {
            return ApiResponse::error('Invalid or expired OTP', null, 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return ApiResponse::success(UserDTO::fromModel($user)->toArray(), 'Password reset successfully');
    }

    public function resendCode(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ApiResponse::error('User not found', null, 404);
        }

        $otp = rand(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        return ApiResponse::success(null, 'OTP resent successfully');
    }

    public function loginGoogle(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'displayName' => 'nullable|string|max:255',
            'photoURL' => 'nullable|url|max:500',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->displayName ?? explode('@', $request->email)[0],
                'avatar' => $request->photoURL,
                'password' => Hash::make(Str::random(32)),
                'created_by' => $request->email,
                'updated_by' => $request->email,
            ]);
        } else {
            $updateData = [
                'updated_by' => $user->email,
            ];

            if ($request->has('displayName') && $request->displayName) {
                $updateData['full_name'] = $request->displayName;
            }

            if ($request->has('photoURL') && $request->photoURL) {
                $updateData['avatar'] = $request->photoURL;
            }

            $user->update($updateData);
        }

        $token = JWTAuth::fromUser($user);
        $userDTO = UserDTO::fromModel($user);
        $loginResponse = new LoginResponseDTO($token, $userDTO);

        return ApiResponse::success($loginResponse->toArray(), 'Login with Google successful');
    }
}
