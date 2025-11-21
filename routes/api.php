<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes (Prefix: /api/v1/...)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ==========================================================
    // 🔓 PUBLIC ROUTES (Login Dulu Di Sini)
    // URL: http://127.0.0.1:8000/api/v1/login
    // ==========================================================
    Route::post('/login', [AuthController::class, 'login']);

    // ==========================================================
    // 🔐 PROTECTED ROUTES (Wajib Pakai Bearer Token)
    // ==========================================================
    Route::middleware('auth:sanctum')->group(function () {

        // 🔸 AUTH MODULE
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // 🔸 MASTER DATA
        // Otomatis bikin route: index, store, show, update, destroy
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/customers', CustomerController::class);
        Route::apiResource('/products', ProductController::class);
        Route::apiResource('/transactions', TransactionController::class);

        // 🔸 RECOMMENDATION MODULE (Core Features)
        // URL: /api/v1/recommendations/generate -> Lari ke function store()
        Route::post('/recommendations/generate', [RecommendationController::class, 'store']); 
        
        // URL: /api/v1/recommendations/history -> Lari ke function index()
        Route::get('/recommendations/history', [RecommendationController::class, 'index']);
        
        // URL: /api/v1/recommendations/{id}/override -> Lari ke function override()
        Route::post('/recommendations/{id}/override', [RecommendationController::class, 'override']);

        // 🔸 DASHBOARD
        // URL: /api/v1/dashboard/stats
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    });
});