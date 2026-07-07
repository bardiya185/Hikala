<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware([
    'auth:sanctum',
    'permission:product.view'
])->get('/test-permission', function () {

    return response()->json([
        'message' => 'Permission OK'
    ]);

});
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
Route::apiResource('roles', RoleController::class);
Route::get('/test', [AuthController::class, 'test']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
