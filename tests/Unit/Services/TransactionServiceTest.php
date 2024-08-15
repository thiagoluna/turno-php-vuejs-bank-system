<?php

namespace Tests\Unit\Services;

use App\DTO\AddPurchaseDTO;
use App\Enums\TransactionStatusEnum;
use App\Exceptions\ErrorToRegisterAPurchaseException;
use App\Exceptions\ErrorToApproveDepositException;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\BankAccountService;
use App\Services\TransactionService;
use Mockery;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    private $bankAccountServiceMock;
    private $bankAccountRepositoryMock;
    private $transactionRepositoryMock;
    private TransactionService $transactionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bankAccountServiceMock = Mockery::mock(BankAccountService::class);
        $this->bankAccountRepositoryMock = Mockery::mock(BankAccountRepositoryInterface::class);
        $this->transactionRepositoryMock = Mockery::mock(TransactionRepositoryInterface::class);

        $this->transactionService = new TransactionService(
            $this->bankAccountServiceMock,
            $this->bankAccountRepositoryMock,
            $this->transactionRepositoryMock
        );
    }

    public function testStorePurchaseFailure()
    {
        $this->expectException(ErrorToRegisterAPurchaseException::class);

        $addPurchaseDTO = new AddPurchaseDTO(
            bank_account_id: 'uuid-bank-account',
            description: 'Purchase description',
            date: '08/14/2024',
            type: 'purchase',
            amount: 100.0,
            status: 'completed',
            image_url: null
        );

        $this->transactionRepositoryMock
            ->shouldReceive('create')
            ->andThrow(new \Exception('Database error'));

        $this->transactionService->storePurchase($addPurchaseDTO);
    }

    public function testApproveDepositFailure()
    {
        $this->expectException(ErrorToApproveDepositException::class);

        $transactionUuid = 'uuid-transaction';

        $this->transactionRepositoryMock
            ->shouldReceive('updateSingle')
            ->with($transactionUuid, 'status', TransactionStatusEnum::APPROVED->value)
            ->andReturn(false);

        $this->transactionService->approveDeposit($transactionUuid);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
