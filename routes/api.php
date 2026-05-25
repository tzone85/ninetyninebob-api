<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Backwards-compat alias for the original /user endpoint
    Route::get('/user', [AuthController::class, 'me']);
});

Route::get('/', function () {
    return response()->json([
        'service' => 'ninetyninebob-api',
        'version' => '1.0.0',
    ]);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
