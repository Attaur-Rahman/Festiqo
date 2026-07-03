<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthService
{
    // Authenticates a user and returns their details with a new API token.
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['login'])
            ->orWhere('phone', $credentials['login'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        if (! $user->status) {
            throw new AccessDeniedHttpException('Your account has been deactivated. Please contact the administrator.');
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    // Returns the authenticated user's profile with assigned roles.
    public function me(User $user): User
    {
        return $user->load('roles');
    }

    // Logs out the user by revoking all active API tokens.
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    // Updates the user's password after verifying the current password.
    public function changePassword(User $user, array $data): void
    {
        if (! Hash::check($data['current_password'], $user->password)) {

            throw ValidationException::withMessages([
                'current_password' => [
                    'Current password is incorrect.'
                ]
            ]);
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $user->tokens()->delete();
    }
}
