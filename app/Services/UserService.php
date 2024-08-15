<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use App\DTO\CreateUserDTO;
use App\Exceptions\ErrorToCreateUserException;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(CreateUserDTO $createUserDTO): User
    {
        try {
            $userData = (array) $createUserDTO;
            $userData['password'] = bcrypt($userData['password']);
            return $this->userRepository->create($userData);
        } catch (Throwable $th) {
            throw new ErrorToCreateUserException($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
