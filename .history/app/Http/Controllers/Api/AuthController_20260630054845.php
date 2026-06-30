<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\OtpCode;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;



class AuthController extends Controller
{


    #[OA\Get(
        path: "/api/test",
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function test()
    {
        return response()->json(['ok' => true]);
    }
   /**
 * @OA\Post(
 *     path="/api/refresh-token",
 *     tags={"Auth"},
 *     summary="Refresh token",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"refresh_token"},
 *             @OA\Property(property="refresh_token", type="string")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="New token"
 *     )
 * )
 */
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);

        $refreshToken = RefreshToken::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (! $refreshToken) {
            return response()->json([
                'status' => 401,
                'message' => 'Refresh Token نامعتبر است.',
            ], 401);
        }

        $user = User::find($refreshToken->user_id);

        if (! $user) {
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
            'status' => true,
            'access_token' => $accessToken,
        ]);
    }

/**
 * @OA\Post(
 *     path="/api/sendOtp",
 *     tags={"Auth"},
 *     summary="Send OTP",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"mobile"},
 *             @OA\Property(
 *                 property="mobile",
 *                 type="string",
 *                 example="09123456789"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OTP Sent"
 *     )
 * )
 */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:11',
        ]);

        $code = random_int(100000, 999999);

        OtpCode::create([
            'mobile' => $request->mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        dump("OTP: {$code}");

        return response()->json([
            'status' => 200,
            'message' => 'کد ورود با موفقیت ارسال شد.',
        ], 200);
    }

 /**
 * @OA\Post(
 *     path="/api/check-otp",
 *     tags={"Auth"},
 *     summary="Login with OTP",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"mobile","code"},
 *             @OA\Property(property="mobile", type="string"),
 *             @OA\Property(property="code", type="string")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Login success"
 *     )
 * )
 */
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

        if (! $otp) {
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

  /**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     summary="Logout user",
 *
 *     @OA\Response(
 *         response=200,
 *         description="Logged out"
 *     )
 * )
 */
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

    // // Register
    // public function register(RegisterRequest $request)
    // {

    //     $data = $request->validated();

    //     $user = User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }

    // // login
    // public function login(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (! $user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     if (! Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid password'], 401);
    //     }

    //     $user->tokens()->delete();

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }

    // logout
    // public function logout(Request $request)
    // {
    //     $user = $request->user();

    //     if (! $user) {
    //         return response()->json([
    //             'message' => 'Unauthenticated',
    //         ], 401);
    //     }

    //     $user->currentAccessToken()->delete();

    //     return response()->json([
    //         'message' => 'Logged out successfully',
    //     ]);
    // }
}
