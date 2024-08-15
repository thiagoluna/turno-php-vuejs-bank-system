<?php

namespace App\Services;

use App\DTO\AddDepositDTO;
use App\Enums\TransactionStatusEnum;
use App\Exceptions\ErrorToRegisterADepositException;
use App\Exceptions\ErrorToApproveDepositException;
use App\Models\Transaction;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use App\DTO\AddPurchaseDTO;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ErrorToRegisterAPurchaseException;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionService
{
    private BankAccountService $bankAccountService;
    private BankAccountRepositoryInterface $bankAccountRepository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        BankAccountService $bankAccountService,
        BankAccountRepositoryInterface $bankAccountRepository,
        TransactionRepositoryInterface $transactionRepository,
    )
    {
        $this->bankAccountService = $bankAccountService;
        $this->bankAccountRepository = $bankAccountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param string $bankAccount
     * @param string|null $type
     * @return Closure|mixed|object|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getTransactions(string $bankAccount, ?string $type): mixed
    {
        if (is_null($type))
            return $this->transactionRepository->findWhereColumn(column: "bank_account_id", value: $bankAccount)
                ->orderBy("date")
                ->getAll();

        return $this->transactionRepository->findWhereColumn(column: "bank_account_id", value: $bankAccount)
            ->orderBy("date")
            ->findWhere('type', $type);
    }

    /**
     * @param string $bankAccount
     * @return array
     */
    public function getBalanceIncomesExpenses(string $bankAccount): array
    {
        $result = $this->bankAccountRepository->getBalanceIncomesExpenses($bankAccount);

        return [
            "balance" => number_format($result[0], 2, ',', ''),
            "incomes" => number_format($result[1], 2, ',', ''),
            "expenses" => number_format($result[2], 2, ',', ''),
        ];
    }

    /**
     * @param AddPurchaseDTO $addPurchaseDTO
     * @return bool
     * @throws ErrorToRegisterAPurchaseException
     */
    public function storePurchase(AddPurchaseDTO $addPurchaseDTO): bool
    {
        try {
            $purchaseData = (array) $addPurchaseDTO;
            $dataObj = \DateTime::createFromFormat('m/d/Y', $purchaseData['date']);
            $formattedDate = $dataObj->format('Y-m-d H:i:s');
            $purchaseData['date'] = $formattedDate;

            $bankAccountVerified = $this->bankAccountService->verifyBalance(
                bankAccountUuid: $purchaseData["bank_account_id"],
                amount: $purchaseData["amount"],
            );

            $transaction = $this->transactionRepository->create(data: $purchaseData);
            /** @var Transaction $transaction */
            if ($transaction) {
                return $this->bankAccountService->decreaseBalance(
                    bankAccount: $bankAccountVerified,
                    amount: $transaction->amount
                );
            }

            return false;
        } catch (Throwable $th) {
            throw new ErrorToRegisterAPurchaseException($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param AddDepositDTO $addDepositDTO
     * @return mixed
     * @throws ErrorToRegisterADepositException
     */
    public function storeDeposit(AddDepositDTO $addDepositDTO): mixed
    {
        try {
            return $this->transactionRepository->create(data: (array) $addDepositDTO);
        } catch (Throwable $th) {
            throw new ErrorToRegisterADepositException($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return Closure|mixed|object|null
     */
    public function getPendingDeposits(): mixed
    {
        return $this->transactionRepository->getPendingDeposits();
    }

    public function getDepositById($id): array
    {
        $deposit = $this->transactionRepository
            ->relationships('bankAccount')
            ->findById($id);

        return [
            "id" => $deposit->id,
            "customer" => $deposit->bankAccount->user->name,
            "email" => $deposit->bankAccount->user->email,
            "account" => $deposit->bankAccount->account,
            "amount" => $deposit->amount,
            "image" => $deposit->image_url
        ];
    }

    /**
     * @param string $transactionUuid
     * @return bool
     * @throws ErrorToApproveDepositException
     */
    public function approveDeposit(string $transactionUuid): bool
    {
        try {
            $approved = $this->transactionRepository->updateSingle(
                id: $transactionUuid,
                field: "status",
                value: TransactionStatusEnum::APPROVED->value
            );

            if ($approved) {
                /** @var Transaction $transaction */
                $transaction = $this->transactionRepository->findWhereFirst(column: 'id', value: $transactionUuid);
                $newBalance = $transaction->bankAccount->balance + $transaction->amount;

                return $this->bankAccountService->increaseBalance(
                    bankAccount: $transaction->bankAccount,
                    amount: $newBalance
                );
            }

            return false;
        } catch (Throwable $th) {
            throw new ErrorToApproveDepositException($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $transactionUuid
     * @return bool
     * @throws ErrorToApproveDepositException
     */
    public function rejectDeposit(string $transactionUuid): bool
    {
        try {
            return $this->transactionRepository->updateSingle(
                id: $transactionUuid,
                field: "status",
                value: TransactionStatusEnum::REJECTED->value
            );
        } catch (Throwable $th) {
            throw new ErrorToApproveDepositException($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
