<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        $user = $this->userRepository->findByName($request->name);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nameOrpassword' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();
        return response()->json([
            "data" => [
                'token' => $user->createToken($request->name)->plainTextToken,
                'user' => new UserResource($user),
            ]
        ]);
    }

    /**
     * @return UserResource
     */
    public function me(): UserResource
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    /**
     * @return Response
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->noContent();
    }
}
