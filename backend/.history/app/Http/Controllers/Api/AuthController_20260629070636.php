<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // گرفتن دیتاهای validate شده
        $data = $request->validated();

        // ساخت کاربر
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // ساخت توکن (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // پاسخ API
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }





public function login(\Illuminate\Http\Request $request)
{
    // 1. پیدا کردن کاربر با ایمیل
   $user = User::where('email', $request->email)->first();

    // 2. اگر کاربر وجود نداشت
    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    // 3. چک کردن پسورد
    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Password is incorrect'
        ], 401);
    }

    // 4. ساخت توکن
    $token = $user->createToken('auth_token')->plainTextToken;

    // 5. برگرداندن پاسخ
    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}
}