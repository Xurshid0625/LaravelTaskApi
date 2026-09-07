<?php

namespace App\Repositories\Actions\Batch;

use Throwable;
use App\Models\Batch;
use App\DTOs\PurchaseDTO;
use Illuminate\Support\Facades\DB;
use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\StorageProductRepository;
use App\Repositories\BatchProductRepository;

class CreateBatchRepoAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(PurchaseDTO $data): Batch
    {
        return DB::transaction(function () use ($data) {
            $batch = BatchRepository::getModel()->create(self::fillBatch($data));

            foreach ($data->products as $product) {
                BatchProductRepository::getModel()->create(self::fillBatchProduct($batch->id, $product));

                StorageProductRepository::getModel()->updateOrCreate(
                    [
                        'storage_id' => $data->storageId,
                        'product_id' => $product['product_id'],
                    ],
                    [
                        'qty' => DB::raw(
                            'qty + ' . (int)$product['qty']
                        ),
                    ]
                );
            }

            return $batch;
        });
    }

    private static function fillBatch(PurchaseDTO $data): array
    {
        return [
            'provider_id' => $data->providerId,
            'storage_id' => $data->storageId,
            'purchased_at' => $data->purchasedAt,
        ];
    }

    private static function fillBatchProduct(int $batchId, array $product): array
    {
        return [
            'batch_id' => $batchId,
            'product_id' => $product['product_id'],
            'qty' => $product['qty'],
            'purchase_price' => $product['purchase_price'],
        ];
    }
}
