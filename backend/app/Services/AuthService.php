<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordOtp;
use App\Notifications\PasswordOtpNotification;
use App\Models\PasswordResetToken;
use Illuminate\Support\Str;

class AuthService
{
    private const OTP_EXPIRY_MINUTES = 10;
    private const MAX_RESEND_ATTEMPTS = 3;

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

    // Generates and sends a password reset OTP to the user.
    public function forgotPassword(array $data): JsonResponse
    {
        $identifier = trim($data['identifier']);

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user) {
            return ApiResponse::error(
                message: 'No account is associated with the provided identifier.',
                status: 404
            );
        }

        $otp = (string) random_int(100000, 999999);

        DB::transaction(function () use ($user, $otp) {

            PasswordOtp::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'otp' => Hash::make($otp),
                    'resend_attempts' => 0,
                    'expires_at' => now()->addMinutes(10),
                ]
            );

            /**
             * Currently sending OTP via email.
             * SMS can be added later.
             */
            $user->notify(
                new PasswordOtpNotification($otp)
            );
        });

        return ApiResponse::success(
            message: 'OTP sent successfully.'
        );
    }

    // Verifies the provided OTP and returns a password reset token.
    public function verifyOtp(array $data): JsonResponse
    {
        $identifier = trim($data['identifier']);

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user) {
            return ApiResponse::error(
                message: 'Invalid OTP.',
                status: 400
            );
        }

        $passwordOtp = PasswordOtp::where('user_id', $user->id)->first();

        if (! $passwordOtp) {
            return ApiResponse::error(
                message: 'Invalid OTP.',
                status: 400
            );
        }

        if ($passwordOtp->expires_at->isPast()) {

            $passwordOtp->delete();

            return ApiResponse::error(
                message: 'OTP has expired.',
                status: 400
            );
        }

        if (! Hash::check($data['otp'], $passwordOtp->otp)) {

            $passwordOtp->increment('attempts');

            return ApiResponse::error(
                message: 'Invalid OTP.',
                status: 400
            );
        }

        $plainToken = Str::random(64);

        DB::transaction(function () use ($user, $passwordOtp, $plainToken) {

            PasswordResetToken::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'token' => Hash::make($plainToken),
                    'expires_at' => now()->addMinutes(15),
                    'created_at' => now(),
                ]
            );

            $passwordOtp->delete();
        });

        return ApiResponse::success(
            data: [
                'reset_token' => $plainToken,
            ],
            message: 'OTP verified successfully.'
        );
    }

    // Resends a new password reset OTP if the resend limit has not been exceeded.
    public function resendOtp(array $data): JsonResponse
    {
        $identifier = trim($data['identifier']);

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user) {
            return ApiResponse::success(
                message: 'OTP has been sent.'
            );
        }

        $passwordOtp = PasswordOtp::where('user_id', $user->id)->first();

        if (! $passwordOtp) {
            return ApiResponse::error(
                message: 'Please request a new password reset.',
                status: 400
            );
        }

        if ($passwordOtp->resend_attempts >= self::MAX_RESEND_ATTEMPTS) {
            return ApiResponse::error(
                message: 'Maximum OTP resend attempts reached.',
                status: 429
            );
        }

        $otp = (string) random_int(100000, 999999);

        DB::transaction(function () use ($passwordOtp, $user, $otp) {

            $passwordOtp->update([
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'resend_attempts' => $passwordOtp->resend_attempts + 1,
            ]);

            $user->notify(new PasswordOtpNotification($otp));
        });

        return ApiResponse::success(
            message: 'OTP resent successfully.'
        );
    }
}
