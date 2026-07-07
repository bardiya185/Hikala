<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\RefreshToken;

class AuthController extends Controller
{
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

        logger()->info("
==================================
OTP : {$code}
Mobile : {$request->mobile}
==================================
");

        return response()->json([
            'status' => true,
            'message' => 'کد ورود با موفقیت ارسال شد.',
        ], 200);
    }

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
                'status' => false,
                'message' => 'کد وارد شده اشتباه است.',
            ], 401);
        }

        if ($otp->expires_at < now()) {
            return response()->json([
                'status' => false,
                'message' => 'کد منقضی شده است.',
            ], 401);
        }

        $otp->update([
            'used_at' => now(),
        ]);

        dd('OTP OK');

        $user = User::firstOrCreate(
            ['mobile' => $request->mobile],
            ['name' => null]
        );

        dd($user);

        $accessToken = $user->createToken('access_token')->plainTextToken;

        $refreshToken = Str::random(80);
        
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $refreshToken),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'ورود با موفقیت انجام شد.',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => $user,
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
