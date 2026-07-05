<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Get(
        path: '/api/test',
        responses: [
            new OA\Response(response: 200, description: 'OK'),
        ]
    )]
    public function test()
    {
        return response()->json(['ok' => true]);
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
                    new OA\Property(
                        property: 'refresh_token',
                        type: 'string',
                        example: 'your_refresh_token_here'
                    ),
                ]
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                description: 'Access token created'
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid refresh token'
            ),
        ]
    )]
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);

        $refreshToken = RefreshToken::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken) {
            return response()->json([
                'status' => 401,
                'message' => 'Refresh Token نامعتبر است.',
            ], 401);
        }

        $user = User::find($refreshToken->user_id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'کاربر پیدا نشد.',
            ], 404);
        }

        // حذف Access Token های قبلی
        $user->tokens()->delete();

        // ساخت Access Token جدید
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'access_token' => $accessToken,
        ]);
    }

    #[OA\Post(
        path: '/api/send-otp',
        tags: ['Auth'],
        summary: 'ارسال کد OTP',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['mobile'],
                properties: [
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        example: '09123456789'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP sent successfully'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function sendOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:11',
        ]);

        $code = random_int(100000, 999999);

        OtpCode::create([
            'mobile' => $request->mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'اطلاعات وارد شده معتبر نیست.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'status' => 200,
            'message' => 'کد با موفقیت ارسال شد',
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
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        example: '09123456789'
                    ),
                    new OA\Property(
                        property: 'code',
                        type: 'string',
                        example: '123456'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login Success'
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid OTP'
            ),
        ]
    )]
    public function checkOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:11',
            'code' => 'required|digits:6',
        ]);

        $otp = OtpCode::where('mobile', $request->mobile)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'status' => 401,
                'message' => 'کد وارد شده اشتباه است.',
            ], 401);
        }

        if ($otp->expires_at < now()) {
            return response()->json([
                'status' => 401,
                'message' => 'کد منقضی شده است.',
            ], 401);
        }

        $otp->update([
            'used_at' => now(),
        ]);

        $user = User::firstOrCreate(
            ['mobile' => $request->mobile],
            ['name' => null]
        );

        // حذف توکن‌های قبلی (اختیاری ولی پیشنهاد می‌شود)
        $user->tokens()->delete();

        // ساخت Access Token
        $accessToken = $user->createToken('access_token')->plainTextToken;

        // ساخت Refresh Token
        $refreshToken = Str::random(80);

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $refreshToken),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'ورود با موفقیت انجام شد.',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => $user,
        ], 200);
    }

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Logout',
        security: [
            ['bearerAuth' => []],
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout success'
            ),
        ]
    )]
    public function logout(Request $request)
    {
        $user = $request->user();

        // حذف Access Token فعلی
        $request->user()->currentAccessToken()->delete();

        // حذف Refresh Token ها
        RefreshToken::where('user_id', $user->id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'با موفقیت خارج شدید.',
        ]);
    }



    #[OA\Get(
        path: '/api/who-Am-I',
        tags: ['Auth'],
        summary: 'who_Am_I',
        security: [
            ['bearerAuth' => []],
        ],
        // responses: [
        //     new OA\Response(
        //         response: 200,
        //         description: 'Logout success'
        //     ),
        // ]
    )]

    //who_am_i

    public function whoAmI(Request $request)
    {
        return $request->user();
    }


}
