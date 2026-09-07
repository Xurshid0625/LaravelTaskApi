<?php

namespace App\Actions\Client;

use App\DTOs\ClientRefundDTO;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\ClientRefundRequest;
use App\Repositories\ClientRefundRepository;

class ClientRefundAction
{
    use AsAction;

    public function __construct(
        protected ClientRefundRequest $request
    )
    {
    }

    public function handle(): array
    {
        $dto = ClientRefundDTO::fromArray(
            $this->request->validated()
        );

        ClientRefundRepository::createRefund($dto);

        return [
            'message' => 'Client refund created successfully.',
        ];
    }
}
