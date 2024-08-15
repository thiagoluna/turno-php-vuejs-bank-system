<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\DTO\CreateUserDTO;
use App\Services\UserService;
use App\Exceptions\ErrorToCreateUserException;
use App\Repositories\Contracts\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Mockery;

class UserServiceTest extends TestCase
{
    protected $userRepositoryMock;
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function testCreateUserSuccess()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Mike Jordan';
        $user->email = 'mike@email.com';
        $user->password = bcrypt('passw0rd');

        $createUserDTO = new CreateUserDTO(
            name: 'Mike Jordan',
            email: 'mike@email.com',
            password: 'passw0rd'
        );

        $this->userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($userData) {
                return $userData['name'] === 'Mike Jordan' &&
                    $userData['email'] === 'mike@email.com' &&
                    password_verify('passw0rd', $userData['password']);
            }))
            ->andReturn($user);

        $result = $this->userService->create($createUserDTO);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Mike Jordan', $result->name);
        $this->assertEquals('mike@email.com', $result->email);
    }

    public function testCreateUserFailure()
    {
        $createUserDTO = new CreateUserDTO(
            name: 'Mike Jordan',
            email: 'mike@email.com',
            password: 'passw0rd'
        );

        $this->userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $this->expectException(ErrorToCreateUserException::class);
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->userService->create($createUserDTO);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
