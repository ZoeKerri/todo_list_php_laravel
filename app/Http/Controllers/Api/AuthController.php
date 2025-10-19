<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\Auth\LoginResponseDTO;
use App\DTOs\Auth\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     */
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

    /**
     * Register a new user.
     */
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

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);

        return ApiResponse::success($userDTO->toArray(), 'Get profile successful');
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return ApiResponse::success(null, 'Successfully logged out');
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        $user = Auth::user();
        $userDTO = UserDTO::fromModel($user);
        $loginResponse = new LoginResponseDTO($newToken, $userDTO);

        return ApiResponse::success($loginResponse->toArray(), 'Token refreshed successfully');
    }
}
