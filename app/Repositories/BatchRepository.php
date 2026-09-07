<?php

namespace App\Repositories;

use App\Models\Batch;
use App\DTOs\PurchaseDTO;
use App\Repositories\Actions\Batch\CreateBatchRepoAction;
use App\Repositories\Actions\Batch\GetBatchProfitRepoAction;
use App\Repositories\Actions\Batch\GetRemainingStorageRepoAction;

class BatchRepository extends CoreRepository
{
    protected static string $model = Batch::class;

    public static function createPurchase(PurchaseDTO $data)
    {
        return CreateBatchRepoAction::run($data);
    }

    public static function getProfit()
    {
        return GetBatchProfitRepoAction::run();
    }

    public static function getRemainingByDate(string $date)
    {
        return GetRemainingStorageRepoAction::run($date);
    }
}
