<?php

namespace App\Http\Controllers\Api;

use App\DTO\CreateUserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use App\Jobs\WriteThrowableLogsJob;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param StoreUserRequest $request
     * @return UserResource|JsonResponse
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        try {
            $user = $this->userService->create(new CreateUserDTO(... $request->only('name', 'email', 'password')));

            return UserResource::make($user);
        } catch (\Throwable $throwable) {
            WriteThrowableLogsJob::dispatch($throwable->toArray() ?? [], true);

            return response()->json(
                [ "message" => "Error to register user." ],
                $throwable->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
