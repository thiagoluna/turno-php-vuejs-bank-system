<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\TransactionStatusEnum;


class TransactionStatusEnumTest extends TestCase
{
    public function testEnumTotalValues(): void
    {
        $enum = new \ReflectionClass(TransactionStatusEnum::class);

        $this->assertCount(3, $enum->getConstants());
    }

    /**
     * @param string $value1
     * @param string $value2
     * @return void
     * @dataProvider enumDataProvider
     */
    public function testEnumValues(string $value1, string $value2): void
    {
        $this->assertEquals($value1, $value2);
    }

    public function enumDataProvider(): array
    {
        return [
            ['approved', TransactionStatusEnum::APPROVED->value],
            ['rejected', TransactionStatusEnum::REJECTED->value],
            ['pending', TransactionStatusEnum::PENDING->value],
        ];
    }
}
