<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;

// --- Route Publik untuk Autentikasi ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Route yang Membutuhkan Autentikasi (Dilindungi Middleware) ---
Route::group(['middleware' => 'api'], function() {

    // Auth-related routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Product-related routes (CRUD)
    Route::apiResource('products', ProductController::class);

});
