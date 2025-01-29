<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'store', 'show']);
    Route::apiResource('wallets', WalletController::class)->only(['index', 'store', 'show']);
    Route::get('/Allwallets', [WalletController::class, 'getAllWallets']);
    Route::get('/users/{id}/wallets', [UserController::class, 'show'])->name('user.wallets');
    Route::apiResource('transactions', TransactionController::class)->only(['store']);
});

