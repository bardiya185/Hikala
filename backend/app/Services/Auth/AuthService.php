<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Generate device fingerprint
     */
    public function generateFingerprint(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->userAgent() ?? 'unknown',
            $request->ip() ?? 'unknown',
            $request->header('X-Device-ID') ?? 'unknown',
        ]));
    }

    /**
     * Limit number of active refresh tokens per user
     */
    public function limitActiveTokens(User $user, int $max = 5): void
    {
        $activeTokens = RefreshToken::where('user_id', $user->id)->count();
        
        if ($activeTokens >= $max) {
            RefreshToken::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->limit($activeTokens - $max + 1)
                ->delete();
        }
    }

    /**
     * Check if OTP is throttled (rate limiting)
     */
    public function isOtpThrottled(string $mobile): bool
    {
        return OtpCode::where('mobile', $mobile)
            ->where('created_at', '>', now()->subMinutes(2))
            ->exists();
    }

    /**
     * Create OTP code
     */
    public function createOtp(string $mobile): array
    {
        // Delete old OTPs for this mobile
        OtpCode::where('mobile', $mobile)->delete();
        
        $code = random_int(100000, 999999);
        
        OtpCode::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
        ]);
        
        Log::info('OTP created', [
            'mobile' => substr($mobile, 0, 4) . '*****',
            'code' => $code, // Remove in production
        ]);
        
        return ['otp' => $code];
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $mobile, string $code): ?OtpCode
    {
        return OtpCode::where('mobile', $mobile)
            ->where('code', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Get or create user by mobile
     */
    public function getOrCreateUser(string $mobile): User
    {
        return User::firstOrCreate(
            ['mobile' => $mobile],
            [
                'name' => null,
                'is_active' => true,
            ]
        );
    }

    /**
     * Create refresh token
     */
    public function createRefreshToken(User $user, string $fingerprint, Request $request): string
    {
        $refreshToken = Str::random(80);
        
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => Hash::make($refreshToken),
            'expires_at' => now()->addDays(30),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'fingerprint' => $fingerprint,
            'last_used_at' => now(),
        ]);
        
        return $refreshToken;
    }

    /**
     * Validate refresh token
     */
    public function validateRefreshToken(string $refreshToken, string $fingerprint): ?RefreshToken
    {
        // Get all valid tokens
        $tokens = RefreshToken::where('expires_at', '>', now())->get();
        
        // Find matching token
        return $tokens->first(function ($token) use ($refreshToken, $fingerprint) {
            return Hash::check($refreshToken, $token->token) &&
                $token->fingerprint === $fingerprint;
        });
    }

    /**
     * Login user and generate tokens
     */
    public function loginUser(User $user, Request $request, string $fingerprint): array
    {
        // Limit active tokens to prevent abuse
        $this->limitActiveTokens($user, 5);
        
        // Create access token
        $accessToken = $user->createToken('access_token')->plainTextToken;
        
        // Create refresh token
        $refreshToken = $this->createRefreshToken($user, $fingerprint, $request);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'mobile' => substr($user->mobile, 0, 4) . '*****',
            'fingerprint' => substr($fingerprint, 0, 8),
        ]);
        
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => new UserResource($user),
        ];
    }

    /**
     * Logout user
     */
    public function logoutUser(User $user): void
    {
        // Delete all refresh tokens for this user
        RefreshToken::where('user_id', $user->id)->delete();
        
        Log::info('User logged out', [
            'user_id' => $user->id,
            'mobile' => substr($user->mobile, 0, 4) . '*****',
        ]);
    }

  
    /**
     * Clean expired refresh tokens (should be run by a scheduled job)
     */
    public function cleanExpiredTokens(): int
    {
        $deleted = RefreshToken::where('expires_at', '<', now())->delete();
        
        Log::info('Cleaned expired refresh tokens', [
            'count' => $deleted,
        ]);
        
        return $deleted;
    }
}