<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\TransactionTypeEnum;


class TransactionTypeEnumTest extends TestCase
{
    public function testEnumTotalValues(): void
    {
        $enum = new \ReflectionClass(TransactionTypeEnum::class);

        $this->assertCount(2, $enum->getConstants());
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
            ['deposit', TransactionTypeEnum::DEPOSIT->value],
            ['purchase', TransactionTypeEnum::PURCHASE->value],
        ];
    }
}
