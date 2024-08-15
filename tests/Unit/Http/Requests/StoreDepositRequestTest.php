<?php

namespace Tests\Unit\Http\Requests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreDepositRequest;
use Tests\TestCase;

class StoreDepositRequestTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthorizesTheRequest()
    {
        $request = new StoreDepositRequest();

        $this->assertTrue($request->authorize());
    }

    public function testValidatesRequiredFields()
    {
        $request = new StoreDepositRequest();

        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The amount field is required.', $validator->errors()->all());
        $this->assertContains('The description field is required.', $validator->errors()->all());
        $this->assertContains('The image field is required.', $validator->errors()->all());
        $this->assertContains('The bank account id field is required.', $validator->errors()->all());
    }

    public function testValidatesNumericAmount()
    {
        $request = new StoreDepositRequest();

        $validator = Validator::make(['amount' => 'abc'], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The amount must be a number.', $validator->errors()->all());
    }

    public function testValidatesImage()
    {
        $request = new StoreDepositRequest();

        $validator = Validator::make(['image' => 'not-an-image'], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The image must be a file of type: jpeg, png, jpg, gif, svg.', $validator->errors()->all());
    }

    public function testValidatesExistingBankAccountId()
    {
        $request = new StoreDepositRequest();

        $validator = Validator::make(['bank_account_id' => 999], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The selected bank account id is invalid.', $validator->errors()->all());
    }
}
