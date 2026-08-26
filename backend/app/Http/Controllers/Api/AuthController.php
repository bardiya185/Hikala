<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\CheckOtpRequest;
use App\Http\Requests\RefreshTokenRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
            new OA\Response(response: 429, description: 'Too many requests'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function sendOtp(SendOtpRequest $request)
    {
        $mobile = $request->mobile;
    
        // Check rate limit
        if ($this->authService->isOtpThrottled($mobile)) {
            Log::info('OTP rate limit exceeded', [
                'mobile' => substr($mobile, 0, 4) . '*****',
                'ip' => $request->ip(),
            ]);
            

            return response()->json([
                'message' => 'Please wait 2 minutes before requesting again.',
            ], Response::HTTP_TOO_MANY_REQUESTS);

        }
        
        // Create OTP
        $otpData = $this->authService->createOtp($mobile);
        
        Log::info('OTP sent successfully', [
            'mobile' => substr($mobile, 0, 4) . '*****',
            'code' => $otpData['otp'] ?? null,//🛑Beta🛑
        ]);

          
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully.',
            'code' => $otpData['otp'], //🛑Beta🛑
        ]);
     

      }

    #[OA\Post(
        path: '/api/check-otp',
        tags: ['Auth'],
        summary: 'Verify OTP and login',
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
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 401, description: 'Invalid OTP'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function checkOtp(CheckOtpRequest $request)
    {
        $mobile = $request->mobile;
        $code = $request->code;
        
        // Verify OTP
        $otp = $this->authService->verifyOtp($mobile, $code);
        
        if (!$otp) {
            Log::warning('Invalid OTP attempt', [
                'mobile' => substr($mobile, 0, 4) . '*****',
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'message' => 'The code entered is incorrect or has expired',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        // Mark OTP as used
        $otp->update(['used_at' => now()]);
        
        // Get or create user
        $user = $this->authService->getOrCreateUser($mobile);
        
        // Generate tokens
        $fingerprint = $this->authService->generateFingerprint($request);
        $tokens = $this->authService->loginUser($user, $request, $fingerprint);
        
        // Create refresh token cookie
        $cookie = Cookie::make(
            'refresh_token',
            $tokens['refresh_token'],
            60 * 24 * 30, // 30 days
            '/',
            null,
            true, // secure (HTTPS only)
            true, // httpOnly
            false,
            'lax'
        );
        
        Log::info('User logged in', [
            'user_id' => $user->id,
            'mobile' => substr($mobile, 0, 4) . '*****',
        ]);
        
        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'user' => $tokens['user'],
        ])->cookie($cookie);
    }

    #[OA\Post(
        path: '/api/refresh-token',
        tags: ['Auth'],
        summary: 'Refresh access token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'refresh_token', type: 'string', example: 'your_refresh_token_here'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Access token refreshed'),
            new OA\Response(response: 401, description: 'Invalid refresh token'),
        ]
    )]
    public function refreshToken(RefreshTokenRequest $request)
    {
        // Try to get refresh token from cookie first, then from request body
        $refreshToken = $request->cookie('refresh_token') ?? $request->refresh_token;
        
        if (!$refreshToken) {
            Log::warning('Refresh token not provided', [
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'message' => 'Refresh token not found.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $fingerprint = $this->authService->generateFingerprint($request);
        
        // Validate refresh token
        $validToken = $this->authService->validateRefreshToken($refreshToken, $fingerprint);
        
        if (!$validToken) {
            Log::warning('Invalid refresh token attempt', [
                'ip' => $request->ip(),
                'fingerprint' => substr($fingerprint, 0, 8),
            ]);
            
            return response()->json([
                'message' => 'Your session has expired or is invalid. Please login again.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $user = $validToken->user;
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }
        
        // Update last used timestamp
        $validToken->update(['last_used_at' => now()]);
        
        // Create new access token
        $accessToken = $user->createToken('access_token')->plainTextToken;
        
        Log::info('Access token refreshed', [
            'user_id' => $user->id,
        ]);
        
        return response()->json([
            'access_token' => $accessToken,
        ]);
    }

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Logout',
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logout successful'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $this->authService->logoutUser($user);
        
        // Clear refresh token cookie
        $cookie = Cookie::forget('refresh_token');
        
        Log::info('User logged out', [
            'user_id' => $user->id,
        ]);
        
        return response()->json([
            'message' => 'You have been successfully logged out.',
        ])->cookie($cookie);
    }

    #[OA\Get(
        path: '/api/who-am-i',
        tags: ['Auth'],
        summary: 'Get authenticated user information',
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: 'User information retrieved'),
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
            ]);
        }
        
        return response()->json([
            'authenticated' => true,
            'data' => new \App\Http\Resources\UserResource($user),
        ]);
    }
}