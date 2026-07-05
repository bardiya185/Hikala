<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use 

// َAuth
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
// Category
Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::post('/', [CategoryController::class, 'store']);

    Route::get('/{category}', [CategoryController::class, 'show']);

    Route::put('/{category}', [CategoryController::class, 'update']);

    Route::delete('/{category}', [CategoryController::class, 'destroy']);

});

Route::apiResource('roles', RoleController::class);
Route::get('/test', [AuthController::class, 'test']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
