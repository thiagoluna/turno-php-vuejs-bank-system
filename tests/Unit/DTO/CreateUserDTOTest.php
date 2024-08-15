<?php

namespace Tests\Unit\DTO;

use App\DTO\CreateUserDTO;
use Tests\TestCase;

class CreateUserDTOTest extends TestCase
{
    public function testCreateUserDTOInitialization()
    {
        $name = 'Mike Jordan';
        $email = 'mike@email.com';
        $password = 'passw0rd';

        $dto = new CreateUserDTO($name, $email, $password);

        $this->assertEquals($name, $dto->name);
        $this->assertEquals($email, $dto->email);
        $this->assertEquals($password, $dto->password);
    }

    public function testCreateUserDTOInvalidEmail()
    {
        $this->expectException(\TypeError::class);

        new CreateUserDTO('Mike Jordan', [], 'passw0rd');
    }

    public function testCreateUserDTOInvalid_name()
    {
        $this->expectException(\TypeError::class);

        new CreateUserDTO([], 'mike@email.com', 'passw0rd');
    }

    public function testCreateUserDTOInvalidPassword()
    {
        $this->expectException(\TypeError::class);

        new CreateUserDTO('Mike Jordan', 'mike@email.com', []);
    }
}
