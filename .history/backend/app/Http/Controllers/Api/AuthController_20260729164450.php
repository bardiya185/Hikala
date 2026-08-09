<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
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
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);

        // Check if token is valid and not expired in the database
        $refreshToken = RefreshToken::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Your session has expired, please login again.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($refreshToken->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Delete previous access token (only the token associated with this process, not all devices)
        // For better security, you can implement single-token mechanism or separate tokens based on Device.
        
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

        // Check code validity: ensure it's active, not used, and not expired directly in query condition
        $otp = OtpCode::where('mobile', $request->mobile)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'The code entered is incorrect or has expired',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Use Transaction to simultaneously record login, consume OTP, and issue tokens
        $responseData = DB::transaction(function () use ($otp, $request) {
            $otp->update([
                'used_at' => now(),
            ]);

            $user = User::firstOrCreate(
                ['mobile' => $request->mobile],
                ['name' => null]
            );

            // Generate tokens
            $accessToken  = $user->createToken('access_token')->plainTextToken;
            $refreshToken = Str::random(80);

            RefreshToken::create([
                'user_id'    => $user->id,
                'token'      => hash('sha256', $refreshToken),
                'expires_at' => now()->addDays(30),
            ]);

            return [
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'user'          => new UserResource($user),
            ];
        });

        return response()->json([
            'message'       => 'Login successful.',
            'access_token'  => $responseData['access_token'],
            'refresh_token' => $responseData['refresh_token'],
            'user'          => $responseData['user'],
        ]);
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
        // Securely delete only the current device's Access Token
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Delete all Refresh Tokens for this user (or you can delete only the current token based on your system architecture)
        RefreshToken::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'You have been successfully logged out.',
        ]);
    }

    #[OA\Get(
        path: '/api/who-am-i',
        tags: ['Auth'],
        summary: 'Who Am I',
        description: 'Get authenticated user information',
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: 'User information retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function whoAmI(Request $request)
    {
        return response()->json([
            'data' => new UserResource($request->user()),
        ]);
    }
}