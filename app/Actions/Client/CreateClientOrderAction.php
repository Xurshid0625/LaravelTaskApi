<?php

namespace App\Actions\Client;

use App\DTOs\OrderDTO;
use App\Repositories\OrderRepository;
use App\Http\Requests\CreateOrderRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientOrderAction
{
    use AsAction;

    public function __construct(protected CreateOrderRequest $request)
    {
    }

    public function handle(): array
    {
        $dto = OrderDTO::fromArray(
            $this->request->validated()
        );

        OrderRepository::createOrder($dto);

        return [
            'message' => 'Client order created successfully.',
        ];
    }
}
