<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingDepositResource;
use App\Http\Resources\PendingDepositsResource;
use App\Jobs\WriteThrowableLogsJob;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param string $id
     * @return PendingDepositResource
     */
    public function getDepositById(string $id): PendingDepositResource
    {
        return PendingDepositResource::make($this->transactionService->getDepositById($id));
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getPendingDeposits(): AnonymousResourceCollection
    {
        $deposits = $this->transactionService->getPendingDeposits();

        return PendingDepositsResource::collection($deposits);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function approveDeposit(string $id): JsonResponse
    {
        try {
            $result = $this->transactionService->approveDeposit(transactionUuid: $id);

            return match ($result) {
                true => response()->json(["message" => "Deposit approved"], Response::HTTP_OK),
                false => response()->json(["error" => "Error to approve deposit"], Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        } catch (\Throwable $throwable) {
            WriteThrowableLogsJob::dispatch($throwable->toArray() ?? [], true);

            return response()->json([ "message" => "Error to approve a Deposit" ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function rejectDeposit(string $id): JsonResponse
    {
        try {
            $result = $this->transactionService->rejectDeposit(transactionUuid: $id);

            return match($result) {
                true => response()->json(["message" => "Deposit reject"], Response::HTTP_OK),
                false => response()->json(["error" => "Error to reject deposit"], Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        } catch (\Throwable $throwable) {
            WriteThrowableLogsJob::dispatch($throwable->toArray() ?? [], true);

            return response()->json([ "message" => "Error to reject a Deposit" ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
