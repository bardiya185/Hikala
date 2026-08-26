<?php

namespace App\Services\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use Carbon\Traits\ToStringFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Stringable;

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
     * Check if OTP is throttled
     */
    public function isOtpThrottled(string $mobile): bool
    {
        return OtpCode::where('mobile', $mobile)
            ->where('created_at', '>', now()->subMinutes(2) )
            ->exists();
    }

    /**
     * Create OTP code
     */
    public function createOtp(string $mobile): array
    {
        $code = random_int(100000, 999999);
        
        OtpCode::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
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
     * Create or get user by mobile
     */
    public function getOrCreateUser(string $mobile): User
    {
        return User::firstOrCreate(
            ['mobile' => $mobile],
            ['name' => null]
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
        $tokens = RefreshToken::where('expires_at', '>', now())->get();
        
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
        // Limit active tokens
        $this->limitActiveTokens($user, 5);
        
        // Create access token
        $accessToken = $user->createToken('access_token')->plainTextToken;
        
        // Create refresh token
        $refreshToken = $this->createRefreshToken($user, $fingerprint, $request);
        
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
        // Delete current access token
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        
        // Delete all refresh tokens
        RefreshToken::where('user_id', $user->id)->delete();
    }
}