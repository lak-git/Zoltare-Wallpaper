<?php

use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\WallpaperApiController;
use Illuminate\Support\Facades\Route;

Route::post('stripe/webhook', StripeWebhookController::class);

// Token issuance for personal access tokens (Sanctum)
Route::post('token', [AuthTokenController::class, 'store']);

// Wallpaper API - protected by Sanctum token auth
Route::middleware('auth:sanctum')->group(function () {
	Route::get('wallpapers', [WallpaperApiController::class, 'index']);
	Route::get('wallpapers/{wallpaper}', [WallpaperApiController::class, 'show']);
});

