<?php

namespace App\Actions\Purchase;

use App\DTOs\PurchaseRefundDTO;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\PurchaseRefundRequest;
use App\Repositories\PurchaseRefundRepository;

class PurchaseRefundAction
{
    use AsAction;

    public function __construct(protected PurchaseRefundRequest $request)
    {
    }

    public function handle(): array
    {
        $dto = PurchaseRefundDTO::fromArray(
            $this->request->validated()
        );

        PurchaseRefundRepository::createRefund($dto);

        return [
            'message' => 'Purchase refund created successfully.',
        ];
    }
}
