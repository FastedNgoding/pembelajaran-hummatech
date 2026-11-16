<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('throttle:auth-limit')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:api', 'throttle:api-limit-standart'])->group(function () {
    Route::get('medicines', [MedicineController::class, 'index']);
    Route::get('medicines/{id}', [MedicineController::class, 'show']);
    Route::post('transaction', [TransactionController::class, 'store']);

    Route::middleware('role:admin')->group(function () {
        Route::post('medicines', [MedicineController::class, 'store']);
        Route::put('medicines/{id}', [MedicineController::class, 'update']);
        Route::delete('medicines/{id}', [MedicineController::class, 'destroy']);

        Route::apiResource('users', UserController::class)->parameters(['users' => 'id']);
    });
});