<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BankAccountService;
use App\Http\Resources\BankAccountResource;

class BankAccountController extends Controller
{
    private BankAccountService $bankAccountService;

    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    /**
     * @param string $bankAccountUuid
     * @return BankAccountResource
     */
    public function getBankAccountByUuid(string $bankAccountUuid): BankAccountResource
    {
        $bankAccount = $this->bankAccountService->getBankAccountByUuid($bankAccountUuid);
        $balanceIncomesExpenses = $this->bankAccountService->getBalanceIncomesExpenses($bankAccountUuid);

        return BankAccountResource::make($bankAccount)->additional($balanceIncomesExpenses);
    }
}
