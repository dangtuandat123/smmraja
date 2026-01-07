<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalletApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin API routes for wallet management (requires Sanctum token)
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Wallet adjustment API - requires admin
    Route::post('/wallet/adjust', [WalletApiController::class, 'adjust'])
        ->middleware('admin')
        ->name('api.wallet.adjust');
    
    // User balance check
    Route::get('/wallet/balance', [WalletApiController::class, 'balance'])
        ->name('api.wallet.balance');
});

// Simple API key authentication for webhook/automation
// Usage: POST /api/v2/wallet/adjust with header "X-API-Key: your-api-key"
Route::prefix('v2')->group(function () {
    Route::post('/wallet/adjust', [WalletApiController::class, 'adjustWithApiKey'])
        ->name('api.v2.wallet.adjust');
});
