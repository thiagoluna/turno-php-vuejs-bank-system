<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    public function testRules()
    {
        $request = new AuthRequest();

        $rules = $request->rules();

        $this->assertEquals([
            'name' => 'required',
            'password' => 'required|string|min:6',
        ], $rules);
    }

    public function testAuthorize()
    {
        $request = new AuthRequest();
        $isAuthorized = $request->authorize();

        $this->assertTrue($isAuthorized);
    }

    public function testValidationFailsWithInvalidData()
    {
        $data = [
            'name' => '',
            'password' => '123',
        ];

        $request = new AuthRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function testValidationPassesWithValidData()
    {
        $data = [
            'name' => 'Mike Jordan',
            'password' => '123456',
        ];

        $request = new AuthRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }
}
