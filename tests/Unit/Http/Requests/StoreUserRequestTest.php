<?php

namespace Tests\Unit\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUserRequest;
use Tests\TestCase;

class StoreUserRequestTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthorizesTheRequest()
    {
        $request = new StoreUserRequest();

        $this->assertTrue($request->authorize());
    }

    public function testValidatesRequiredFields()
    {
        $request = new StoreUserRequest();

        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The name field is required.', $validator->errors()->all());
        $this->assertContains('The email field is required.', $validator->errors()->all());
        $this->assertContains('The password field is required.', $validator->errors()->all());
    }

    public function testValidatesUniqueName()
    {
        $request = new StoreUserRequest();

        $existingUser = User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ]);

        $validator = Validator::make([
            'name' => $existingUser->name,
            'email' => 'new@example.com',
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The name has already been taken.', $validator->errors()->all());
    }

    public function testValidatesUniqueEmail()
    {
        $request = new StoreUserRequest();

        $existingUser = User::create([
            'name' => 'Unique User',
            'email' => 'unique@example.com',
            'password' => 'password123',
        ]);

        $validator = Validator::make([
            'name' => 'New User',
            'email' => $existingUser->email,
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The email has already been taken.', $validator->errors()->all());
    }

    public function testValidatesPasswordLength()
    {
        $request = new StoreUserRequest();

        $validator = Validator::make([
            'name' => 'Valid Name',
            'email' => 'valid@example.com',
            'password' => 'short',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The password must be at least 6 characters.', $validator->errors()->all());
    }
}
