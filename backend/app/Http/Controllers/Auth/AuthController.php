<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Injects the authentication service into the controller.
    public function __construct(
        protected AuthService $authService
    ) {}

    // Authenticates the user and returns their profile with an access token.
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return ApiResponse::success(
            data: [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
            message: 'Login successful.'
        );
    }

    // Returns the authenticated user's profile information.
    public function me(Request $request): JsonResponse
    {
        $user = $this->authService->me($request->user());

        return ApiResponse::success(
            data: new UserResource($user),
            message: 'User fetched successfully.'
        );
    }

    // Logs out the authenticated user by revoking their access tokens.
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }

    // Changes the authenticated user's password after validation.
    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            message: 'Password changed successfully. Please login again.'
        );
    }
}
