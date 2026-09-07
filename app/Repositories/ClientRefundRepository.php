<?php

namespace App\Repositories;

use App\Models\ClientRefund;
use App\DTOs\ClientRefundDTO;
use App\Repositories\Actions\Client\CreateClientRefundRepoAction;

class ClientRefundRepository extends CoreRepository
{
    protected static string $model = ClientRefund::class;

    public static function createRefund(ClientRefundDTO $data): ClientRefund
    {
        return CreateClientRefundRepoAction::run($data);
    }
}
