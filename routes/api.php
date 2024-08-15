<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\Admin\AdminController;

Route::post('/auth', [AuthController::class, 'auth'])->name('user.auth');
Route::post('/v1/user', [UserController::class, 'store'])->name('user.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('user.me');

    Route::prefix('v1')->group(function () {
        Route::post('/purchase', [TransactionController::class, 'storePurchase'])->name('user.store.purchase');

        // Transactions
        Route::get('/transactions', [TransactionController::class, 'getTransactions'])->name('transactions.get');

        // Bank Account
        Route::get('/bank-account/{uuid}', [BankAccountController::class, 'getBankAccountByUuid'])->name('bank.account.uuid.get');

        // Deposits
        Route::post('/deposit', [TransactionController::class, 'storeDeposit'])->name('deposit.store');

        // User Admin
        Route::group([
            'prefix' => 'admin',
            'middleware' => 'is_admin',
            'as' => 'admin.'
        ], function () {
            Route::get('/deposits', [AdminController::class, 'getPendingDeposits'])->name('deposits.get');
            Route::get('/deposits/{id}', [AdminController::class, 'getDepositById'])->name('deposit.get.id');
            Route::put('/deposits/approve/{id}', [AdminController::class, 'approveDeposit'])->name('deposit.approve');
            Route::put('/deposits/reject/{id}', [AdminController::class, 'rejectDeposit'])->name('deposit.reject');
        });
    });
});
