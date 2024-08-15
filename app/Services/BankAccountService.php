<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\BankAccount;
use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountService
{
    private BankAccountRepositoryInterface $bankAccountRepository;

    /**
     * @param BankAccountRepositoryInterface $bankAccountRepository
     */
    public function __construct(BankAccountRepositoryInterface $bankAccountRepository)
    {
        $this->bankAccountRepository = $bankAccountRepository;
    }

    /**
     * @param string $bankAccountUuid
     * @return BankAccount
     */
    public function getBankAccountByUuid(string $bankAccountUuid): BankAccount
    {
        return $this->bankAccountRepository->relationships("transactions")->findById($bankAccountUuid);
    }

    /**
     * @param $bankAccountUuid
     * @return array
     */
    public function getBalanceIncomesExpenses($bankAccountUuid): array
    {
        return $this->bankAccountRepository->getBalanceIncomesExpenses($bankAccountUuid);
    }

    /**
     * @param string $bankAccountUuid
     * @param float $amount
     * @return BankAccount
     * @throws InsufficientBalanceException
     */
    public function verifyBalance(string $bankAccountUuid, float $amount): BankAccount
    {
        $bankAccount = $this->bankAccountRepository->findWhereFirst('id', $bankAccountUuid);

        if ($bankAccount->balance > $amount)
            return $bankAccount;

        Throw new InsufficientBalanceException("Insufficient balance");
    }

    /**
     * @param BankAccount $bankAccount
     * @param float $amount
     * @return bool
     */
    public function increaseBalance(BankAccount $bankAccount, float $amount): bool
    {
        $newBalance = $bankAccount->balance + $amount;

        return $this->bankAccountRepository->updateBalance(
            bankAccount: $bankAccount,
            newBalance: $newBalance
        );
    }

    /**
     * @param BankAccount $bankAccount
     * @param float $amount
     * @return bool
     */
    public function decreaseBalance(BankAccount $bankAccount, float $amount): bool
    {
        $newBalance = $bankAccount->balance - $amount;

        return $this->bankAccountRepository->updateBalance(
            bankAccount: $bankAccount,
            newBalance: $newBalance
        );
    }
}
