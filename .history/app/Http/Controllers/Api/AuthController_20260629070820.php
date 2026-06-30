<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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


    
    
    public function login(Request $request)
    {
        // 1. گرفتن کاربر
        $user = User::where('email', $request->email)->first();
    
        // 2. اگر کاربر وجود نداشت
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    
        // 3. بررسی پسورد
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password'
            ], 401);
        }
    
        // 4. حذف توکن‌های قبلی (امنیت بهتر)
        $user->tokens()->delete();
    
        // 5. ساخت توکن جدید
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // 6. پاسخ نهایی
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}