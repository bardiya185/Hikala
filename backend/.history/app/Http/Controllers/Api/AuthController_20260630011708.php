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

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="HiKala API",
 *     version="1.0.0"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000"
 * )
 */
class AuthController extends Controller
{
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);

       // refreshToken

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
    'status' => 200,
    'access_token' => $accessToken,
], 200);
// sendOtp

return response()->json([
    'status' => 200,
    'message' => 'کد ورود با موفقیت ارسال شد.',
], 200);
// checkOtp

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

// حذف توکن‌های قبلی
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
// logout

return response()->json([
    'status' => 200,
    'message' => 'با موفقیت خارج شدید.',
], 200);
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
