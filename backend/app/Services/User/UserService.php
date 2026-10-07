<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Get all users.
     */
    public function index(array $filters)
    {
        $query = User::query()->with('roles');

        // Search by name, email or phone.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter users by status.
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter users by role.
        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        return $query
            ->latest()
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    /**
     * Create a new user.
     */
    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'status' => $data['status'] ?? true,
            ]);

            $user->assignRole($data['role']);

            return $user;
        });
    }

    /**
     * Get a user by ID.
     */
    public function show(User $user): User
    {
        return $user;
    }

    /**
     * Update user details.
     */
    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        return $user->fresh();
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): void
    {
        $user->delete();
    }

    /**
     * Update user status.
     */
    public function updateStatus(User $user, bool $status): User
    {
        $user->update([
            'status' => $status,
        ]);

        return $user->fresh();
    }

    /**
     * Update user role.
     */
    public function updateRole(User $user, string $role): User
    {
        Role::findByName($role, 'sanctum');

        $user->syncRoles([$role]);

        return $user->fresh();
    }
}
