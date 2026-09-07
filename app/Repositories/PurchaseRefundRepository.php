<?php

namespace App\Repositories;

use App\Models\PurchaseRefund;
use App\DTOs\PurchaseRefundDTO;
use App\Repositories\Actions\Purchase\CreatePurchaseRefundRepoAction;

class PurchaseRefundRepository extends CoreRepository
{
    protected static string $model = PurchaseRefund::class;

    public static function createRefund(PurchaseRefundDTO $data): PurchaseRefund
    {
        return CreatePurchaseRefundRepoAction::run($data);
    }
}
