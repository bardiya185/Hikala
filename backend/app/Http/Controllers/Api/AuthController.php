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

        // بررسی ولید بودن و منقضی نشدن توکن به صورت همزمان در دیتابیس
        $refreshToken = RefreshToken::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken) {
            return response()->json([
                'message' => 'نشست شما منقضی شده است، لطفاً مجدداً وارد شوید.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($refreshToken->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'کاربر یافت نشد.',
            ], Response::HTTP_NOT_FOUND);
        }

        // حذف توکن دسترسی قبلی (فقط توکن متصل به این فرآیند، نه همه دستگاه‌ها)
        // برای امنیت بیشتر، می‌توانید مکانیزم تک توکنی یا توکن‌های مجزا براساس Device تعریف کنید.
        
        // ساخت Access Token جدید
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return response()->json([
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
        // ۱. ابتدا ولیدیشن انجام می‌شود تا دیتای خراب وارد دیتابیس نشود
        $request->validate([
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'], // ولیدیشن دقیق‌تر برای شماره موبایل‌های ایران
        ]);

        // ۲. جلوگیری از اسپم (Rate Limiting تجربی): بررسی اینکه در ۲ دقیقه اخیر کدی صادر نشده باشد
        $throttled = OtpCode::where('mobile', $request->mobile)
            ->where('created_at', '>', now()->subMinutes(2))
            ->exists();

        if ($throttled) {
            return response()->json([
                'message' => 'لطفاً قبل از درخواست مجدد، ۲ دقیقه صبر کنید.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $code = random_int(100000, 999999);

        // ذخیره در دیتابیس
        OtpCode::create([
            'mobile'     => $request->mobile,
            'code'       => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        // TODO: در این بخش متد ارسال SMS خود را صدا بزنید.

        return response()->json([
            'message' => 'کد تایید با موفقیت ارسال شد.',
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

        // بررسی دقیق کد: زنده بودن و مصرف نشدن کد مستقیماً در شرط کوئری
        $otp = OtpCode::where('mobile', $request->mobile)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'کد وارد شده اشتباه است یا منقضی شده است.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // استفاده از Transaction جهت ثبت همزمان ورود، مصرف کد و صدور توکن‌ها
        $responseData = DB::transaction(function () use ($otp, $request) {
            $otp->update([
                'used_at' => now(),
            ]);

            $user = User::firstOrCreate(
                ['mobile' => $request->mobile],
                ['name' => null]
            );

            // ساخت توکن‌ها
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
            'message'       => 'ورود با موفقیت انجام شد.',
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
        // حذف امن فقط برای Access Token فعلی دستگاه جاری
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // حذف همه‌ی Refresh Token های این کاربر (یا می‌توانید بر اساس ساختار سیستم فقط توکن جاری را حذف کنید)
        RefreshToken::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'با موفقیت از حساب کاربری خود خارج شدید.',
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