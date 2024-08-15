<?php

namespace App\Repositories;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function model(): string
    {
        return Transaction::class;
    }

    public function getPendingDeposits()
    {
        return User::select('users.name', 'transactions.amount', 'transactions.status', 'transactions.id', 'transactions.date')
            ->join('bank_accounts', 'bank_accounts.user_id', '=', 'users.id')
            ->join('transactions', 'transactions.bank_account_id', '=', 'bank_accounts.id')
            ->where('transactions.type', TransactionTypeEnum::DEPOSIT->value)
             ->where('transactions.status', TransactionStatusEnum::PENDING->value)
            ->get();
    }
}
