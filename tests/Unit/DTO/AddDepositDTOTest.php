<?php

namespace Tests\Unit\DTO;

use App\DTO\AddDepositDTO;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use Tests\TestCase;

class AddDepositDTOTest extends TestCase
{
    public function testAddDepositDTOInitialization()
    {
        $bank_account_id = '171';
        $description = 'Description';
        $date = '2024-08-14';
        $type = TransactionTypeEnum::DEPOSIT->value;
        $amount = 250.75;
        $status = TransactionStatusEnum::APPROVED->value;
        $image_url = '/images/foo.jpg';

        $dto = new AddDepositDTO(
            $bank_account_id,
            $description,
            $date,
            $type,
            $amount,
            $status,
            $image_url
        );

        $this->assertEquals($bank_account_id, $dto->bank_account_id);
        $this->assertEquals($description, $dto->description);
        $this->assertEquals($date, $dto->date);
        $this->assertEquals($type, $dto->type);
        $this->assertEquals($amount, $dto->amount);
        $this->assertEquals($status, $dto->status);
        $this->assertEquals($image_url, $dto->image_url);
    }

    public function testAddDepositDTOInitializationWithNullImageUrl()
    {
        $bank_account_id = '171';
        $description = 'Description';
        $date = '2024-08-14';
        $type = TransactionTypeEnum::DEPOSIT->value;
        $amount = 250.75;
        $status = TransactionStatusEnum::APPROVED->value;
        $image_url = null;

        $dto = new AddDepositDTO(
            $bank_account_id,
            $description,
            $date,
            $type,
            $amount,
            $status,
            $image_url
        );

        $this->assertNull($dto->image_url);
    }

    public function testAddDepositDTOInvalidAmount()
    {
        $this->expectException(\TypeError::class);

        new AddDepositDTO(
            '171',
            'Description',
            '2024-08-14',
            TransactionTypeEnum::DEPOSIT->value,
            'invalid_amount',
            TransactionStatusEnum::APPROVED->value,
            '/images/foo.jpg'
        );
    }

    public function testAddDepositDTOInvalidDate()
    {
        $this->expectException(\TypeError::class);

        new AddDepositDTO(
            '171',
            'Description',
            [],
            TransactionTypeEnum::DEPOSIT->value,
            250.75,
            TransactionStatusEnum::APPROVED->value,
            '/images/foo.jpg'
        );
    }

    public function testAddDepositDTOInvalidStatus()
    {
        $this->expectException(\TypeError::class);

        new AddDepositDTO(
            '171',
            'Description',
            '2024-08-14',
            TransactionTypeEnum::DEPOSIT->value,
            250.75,
            [],
            '/images/foo.jpg'
        );
    }
}
