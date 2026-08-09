<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    /**
     * Generate device fingerprint
     */
    private function generateFingerprint(Request $request): string
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
    private function limitActiveTokens(User $user, int $max = 5): void
    {
        $activeTokens = RefreshToken::where('user_id', $user->id)->count();
        if ($activeTokens >= $max) {
            RefreshToken::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->limit($activeTokens - $max + 1)
                ->delete();
        }
    }

    #[OA\Post(
        path: '/api/refresh-token',
        tags: ['Auth'],
        summary: 'Refresh access token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['refresh_token'],
                properties: [
                    new OA\Property(property: 'refresh_token', type: 'string', example: 'your_refresh_token_here'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Access token created'),
            new OA\Response(response: 401, description: 'Invalid or expired refresh token'),
        ]
    )]
    public function refreshToken(Request $request)
    {
        // Try to get refresh token from cookie first, then from request body
        $refreshToken = $request->cookie('refresh_token') ?? $request->refresh_token;
        
        if (!$refreshToken) {
            return response()->json([
                'message' => 'Refresh token not found.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $fingerprint = $this->generateFingerprint($request);
        
        // Get all active tokens and check each one
        $tokens = RefreshToken::where('expires_at', '>', now())
            ->get();
        
        $validToken = $tokens->first(function ($token) use ($refreshToken, $fingerprint) {
            return Hash::check($refreshToken, $token->token) &&
                $token->fingerprint === $fingerprint;
        });

        if (!$validToken) {
            Log::warning('Failed refresh token attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'fingerprint' => substr($fingerprint, 0, 8),
            ]);
            
            return response()->json([
                'message' => 'Your session has expired or is invalid. Please login again.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($validToken->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Update last used timestamp
        $validToken->update(['last_used_at' => now()]);

        // Create new Access Token
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }

    #[OA\Post(
        path: '/api/send-otp',
        tags: ['Auth'],
        summary: 'Send OTP code',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['mobile'],
                properties: [
                    new OA\Property(property: 'mobile', type: 'string', example: '09123456789'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'OTP sent successfully'),
            new OA\Response(response: 429, description: 'Too many requests - Rate limit exceeded'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
        ]);

        $throttled = OtpCode::where('mobile', $request->mobile)
            ->where('created_at', '>', now()->subMinutes(2))
            ->exists();

        if ($throttled) {
            Log::info('OTP rate limit exceeded', [
                'mobile' => substr($request->mobile, 0, 4) . '*****',
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'message' => 'Please wait 2 minutes before requesting again.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $code = random_int(100000, 999999);

        OtpCode::create([
            'mobile'     => $request->mobile,
            'code'       => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        // TODO: Send SMS with code
        // sendSms($request->mobile, $code);

        return response()->json([
            'message' => 'Verification code sent successfully.',
        ]);
    }

    #[OA\Post(
        path: '/api/check-otp',
        tags: ['Auth'],
        summary: 'Verify OTP',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['mobile', 'code'],
                properties: [
                    new OA\Property(property: 'mobile', type: 'string', example: '09123456789'),
                    new OA\Property(property: 'code', type: 'string', example: '123456'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login Success'),
            new OA\Response(response: 401, description: 'Invalid or expired OTP'),
        ]
    )]
    public function checkOtp(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'code'   => 'required|digits:6',
        ]);

        $otp = OtpCode::where('mobile', $request->mobile)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            Log::warning('Invalid OTP attempt', [
                'mobile' => substr($request->mobile, 0, 4) . '*****',
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'message' => 'The code entered is incorrect or has expired',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $responseData = DB::transaction(function () use ($otp, $request) {
            $otp->update([
                'used_at' => now(),
            ]);

            $user = User::firstOrCreate(
                ['mobile' => $request->mobile],
                ['name' => null]
            );

            // Limit active tokens to prevent abuse
            $this->limitActiveTokens($user, 5);

            $accessToken = $user->createToken('access_token')->plainTextToken;
            $refreshToken = Str::random(80);
            $fingerprint = $this->generateFingerprint($request);

            RefreshToken::create([
                'user_id'     => $user->id,
                'token'       => Hash::make($refreshToken),
                'expires_at'  => now()->addDays(30),
                'user_agent'  => $request->userAgent(),
                'ip_address'  => $request->ip(),
                'fingerprint' => $fingerprint,
                'last_used_at' => now(),
            ]);

            return [
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'user'          => new UserResource($user),
            ];
        });

        // Store refresh token in HttpOnly secure cookie
        $cookie = Cookie::make(
            'refresh_token',
            $responseData['refresh_token'],
            60 * 24 * 30, // 30 days
            '/',
            null,
            true, // secure (HTTPS only)
            true, // httpOnly (not accessible via JavaScript)
            false,
            'lax'
        );

        return response()->json([
            'message'       => 'Login successful.',
            'access_token'  => $responseData['access_token'],
            'user'          => $responseData['user'],
        ])->cookie($cookie);
    }

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Logout',
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logout success'),
        ]
    )]
    public function logout(Request $request)
    {
        $user = $request->user();
        
        // Delete current access token
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        // Delete all refresh tokens for this user
        RefreshToken::where('user_id', $user->id)->delete();

        // Clear refresh token cookie
        $cookie = Cookie::forget('refresh_token');

        return response()->json([
            'message' => 'You have been successfully logged out.',
        ])->cookie($cookie);
    }

#[OA\Get(
    path: '/api/who-am-i',
    tags: ['Auth'],
    summary: 'Who Am I',
    description: 'Get authenticated user information or auth status',
    security: [["bearerAuth" => []]],
    responses: [
        new OA\Response(response: 200, description: 'Auth status retrieved successfully'),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
public function whoAmI(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'authenticated' => false,
            'data' => null,
        ], 200);
    }

    return response()->json([
        'authenticated' => true,
        'data' => new UserResource($user),
    ]);
}
}