<?php

namespace Tests\Unit\Services;

use Mockery;
use Tests\TestCase;
use App\Models\BankAccount;
use App\Services\BankAccountService;
use App\Exceptions\InsufficientBalanceException;
use App\Repositories\Contracts\BankAccountRepositoryInterface;

class BankAccountServiceTest extends TestCase
{
    protected $bankAccountRepositoryMock;
    protected $bankAccountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bankAccountRepositoryMock = Mockery::mock(BankAccountRepositoryInterface::class);
        $this->bankAccountService = new BankAccountService($this->bankAccountRepositoryMock);
    }

    public function testGetBankAccountByUuidSuccess()
    {
        $bankAccountUuid = 'uuid123';
        $bankAccount = new BankAccount();
        $bankAccount->id = 1;
        $bankAccount->balance = 1000.0;

        $this->bankAccountRepositoryMock
            ->shouldReceive('relationships')
            ->with('transactions')
            ->andReturnSelf();

        $this->bankAccountRepositoryMock
            ->shouldReceive('findById')
            ->with($bankAccountUuid)
            ->andReturn($bankAccount);

        $result = $this->bankAccountService->getBankAccountByUuid($bankAccountUuid);

        $this->assertInstanceOf(BankAccount::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals(1000.0, $result->balance);
    }

    public function testGetBalanceIncomesExpensesSuccess()
    {
        $bankAccountUuid = 'uuid123';
        $balanceDetails = [
            'balance' => 1000.0,
            'incomes' => 5000.0,
            'expenses' => 4000.0,
        ];

        $this->bankAccountRepositoryMock
            ->shouldReceive('getBalanceIncomesExpenses')
            ->with($bankAccountUuid)
            ->andReturn($balanceDetails);

        $result = $this->bankAccountService->getBalanceIncomesExpenses($bankAccountUuid);

        $this->assertIsArray($result);
        $this->assertEquals($balanceDetails, $result);
    }

    public function testVerifyBalanceSuccess()
    {
        $bankAccountUuid = 'uuid123';
        $amount = 500.0;
        $bankAccount = new BankAccount();
        $bankAccount->balance = 1000.0;

        $this->bankAccountRepositoryMock
            ->shouldReceive('findWhereFirst')
            ->with('id', $bankAccountUuid)
            ->andReturn($bankAccount);

        $result = $this->bankAccountService->verifyBalance($bankAccountUuid, $amount);

        $this->assertInstanceOf(BankAccount::class, $result);
    }

    public function testVerifyBalanceInsufficientFunds()
    {
        $bankAccountUuid = 'uuid123';
        $amount = 1500.0;
        $bankAccount = new BankAccount();
        $bankAccount->balance = 1000.0;

        $this->bankAccountRepositoryMock
            ->shouldReceive('findWhereFirst')
            ->with('id', $bankAccountUuid)
            ->andReturn($bankAccount);

        $this->expectException(InsufficientBalanceException::class);

        $this->bankAccountService->verifyBalance($bankAccountUuid, $amount);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
