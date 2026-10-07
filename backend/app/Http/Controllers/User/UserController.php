<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Requests\User\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(IndexUserRequest $request): JsonResponse
    {
        $users = $this->userService->index($request->validated());

        return ApiResponse::success(
            data: UserResource::collection($users),
            message: 'Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->store($request->validated());

        return ApiResponse::success(
            message: 'User created successfully.',
            data: new UserResource($user),
            status: 201
        );
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        return ApiResponse::success(
            message: 'User retrieved successfully.',
            data: new UserResource(
                $this->userService->show($user)
            )
        );
    }

    /**
     * Update the specified user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->userService->update(
            $user,
            $request->validated()
        );

        return ApiResponse::success(
            message: 'User updated successfully.',
            data: new UserResource($user)
        );
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->destroy($user);

        return ApiResponse::success(
            message: 'User deleted successfully.'
        );
    }

    /**
     * Update user status.
     */
    public function updateStatus(
        UpdateUserStatusRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->userService->updateStatus(
            $user,
            $request->validated()['status']
        );

        return ApiResponse::success(
            message: 'User status updated successfully.',
            data: new UserResource($user)
        );
    }

    /**
     * Update user role.
     */
    public function updateRole(
        UpdateUserRoleRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->userService->updateRole(
            $user,
            $request->validated()['role']
        );

        return ApiResponse::success(
            message: 'User role updated successfully.',
            data: new UserResource($user)
        );
    }
}
