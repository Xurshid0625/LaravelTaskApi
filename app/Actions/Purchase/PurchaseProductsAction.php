<?php

namespace App\Actions\Purchase;

use App\DTOs\PurchaseDTO;
use App\Repositories\BatchRepository;
use App\Http\Requests\PurchaseRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class PurchaseProductsAction
{
    use AsAction;

    public function __construct(protected PurchaseRequest $request)
    {
    }

    public function handle(): array
    {
        $dto = PurchaseDTO::fromArray(
            $this->request->validated()
        );
        BatchRepository::createPurchase($dto);

        return [
            'message' => 'Products purchased successfully.',
        ];
    }
}
