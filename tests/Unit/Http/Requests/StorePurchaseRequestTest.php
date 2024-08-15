<?php

namespace Tests\Unit\Http\Requests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePurchaseRequest;
use Tests\TestCase;

class StorePurchaseRequestTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthorizesTheRequest()
    {
        $request = new StorePurchaseRequest();

        $this->assertTrue($request->authorize());
    }

    public function testValidatesRequiredFields()
    {
        $request = new StorePurchaseRequest();

        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The amount field is required.', $validator->errors()->all());
        $this->assertContains('The description field is required.', $validator->errors()->all());
        $this->assertContains('The date field is required.', $validator->errors()->all());
        $this->assertContains('The bank account id field is required.', $validator->errors()->all());
    }

    public function testValidatesNumericAmount()
    {
        $request = new StorePurchaseRequest();

        $validator = Validator::make(['amount' => 'abc'], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The amount must be a number.', $validator->errors()->all());
    }

    public function testValidatesDateFormat()
    {
        $request = new StorePurchaseRequest();

        $validator = Validator::make(['date' => '2024-08-14'], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The date does not match the format m/d/Y.', $validator->errors()->all());
    }

    public function testValidatesExistingBankAccountId()
    {
        $request = new StorePurchaseRequest();

        $validator = Validator::make(['bank_account_id' => 999], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The selected bank account id is invalid.', $validator->errors()->all());
    }
}
