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

// Admin API routes for wallet management
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Wallet adjustment API - requires admin
    Route::post('/wallet/adjust', [WalletApiController::class, 'adjust'])
        ->middleware('admin')
        ->name('api.wallet.adjust');
    
    // User balance check
    Route::get('/wallet/balance', [WalletApiController::class, 'balance'])
        ->name('api.wallet.balance');
});
