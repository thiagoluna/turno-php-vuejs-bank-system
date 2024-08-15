<?php

namespace App\Repositories;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\BankAccount;
use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountRepository extends BaseRepository implements BankAccountRepositoryInterface
{
    public function model(): string
    {
        return BankAccount::class;
    }

    /**
     * @param BankAccount $bankAccount
     * @param float $newBalance
     * @return bool
     */
    public function updateBalance(BankAccount $bankAccount, float $newBalance): bool
    {
        return $bankAccount->update(['balance' => $newBalance]);
    }

    /**
     * @param $bankAccountUuid
     * @return array
     */
    public function getBalanceIncomesExpenses($bankAccountUuid): array
    {
        $bankAccount = $this->model->where('id', $bankAccountUuid)->first();

        $totalIncomes = $bankAccount->transactions
            ->where('type', TransactionTypeEnum::DEPOSIT->value)
            ->where('status', TransactionStatusEnum::APPROVED->value)
            ->sum('amount');

        $totalPurchases = $bankAccount->transactions
            ->where('type', TransactionTypeEnum::PURCHASE->value)
            ->sum('amount');

        return [$bankAccount->balance, $totalIncomes, $totalPurchases];
    }
}
