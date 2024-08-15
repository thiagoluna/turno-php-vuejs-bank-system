<?php

namespace App\Http\Controllers\Api;

use App\DTO\AddDepositDTO;
use App\DTO\AddPurchaseDTO;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\TransactionResource;
use App\Jobs\WriteThrowableLogsJob;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getTransactions(Request $request): AnonymousResourceCollection
    {
        $transaction = $this->transactionService->getTransactions($request->get("bank_account_id"), $request->get('type'));
        $balanceIncomesExpenses = $this->transactionService->getBalanceIncomesExpenses($request->get("bank_account_id"));

        return TransactionResource::collection($transaction)->additional($balanceIncomesExpenses);
    }

    /**
     * @param StorePurchaseRequest $request
     * @return JsonResponse
     */
    public function storePurchase(StorePurchaseRequest $request): JsonResponse
    {
        try {
            $addPurchaseDTO = new AddPurchaseDTO(
                $request->input('bank_account_id'),
                $request->input('description'),
                $request->input('date'),
                TransactionTypeEnum::PURCHASE->value,
                $request->input('amount'),
                TransactionStatusEnum::APPROVED->value,
                $request->input('image_url', null),
            );
            $result = $this->transactionService->storePurchase($addPurchaseDTO);

            return match ($result) {
                true => response()->json(["message" => "Purchase added."], Response::HTTP_CREATED),
                false => response()->json(["error" => "Purchase not added"], Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        } catch (\Throwable $throwable) {
            WriteThrowableLogsJob::dispatch($throwable->toArray(), true);

            return response()->json([ "message" => $throwable->getMessage() ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StoreDepositRequest $request
     * @return JsonResponse
     */
    public function storeDeposit(StoreDepositRequest $request): JsonResponse
    {
        try {
            $upload = false;

            if ($request->hasFile("image") && $request->file("image")->isValid()) {
                $name = $request->input("description");
                $microTime = microtime();
                $newName = str_replace(".", "", "{$name}.{$microTime}");
                $extension = $request->file("image")->getClientOriginalExtension();
                $fileName = Str::kebab("{$newName}.{$extension}");

                $upload = $request->file("image")->storeAs("deposits", $fileName);
            }

            if ($upload) {
                $addDepositDTO = new AddDepositDTO(
                    bank_account_id: $request->input('bank_account_id'),
                    description: $request->input('description'),
                    date: Carbon::now()->format('Y-m-d H:i:s'),
                    type: TransactionTypeEnum::DEPOSIT->value,
                    amount: $request->input('amount'),
                    status: TransactionStatusEnum::PENDING->value,
                    image_url: $fileName,
                );

                $this->transactionService->storeDeposit($addDepositDTO);

                return response()->json([], Response::HTTP_CREATED);
            }

            return response()->json(["error" => "Error to register a deposit"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $throwable) {
            $message = [
                "message" => $throwable->getMessage(),
                "line" => $throwable->getLine(),
                "file" => $throwable->getFile()
            ];
            WriteThrowableLogsJob::dispatch($message, true);

            return response()->json([ "message" => "Error to register a Deposit" ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
