<?php

namespace App\Repositories\Actions\Purchase;

use Throwable;
use App\Models\PurchaseRefund;
use App\DTOs\PurchaseRefundDTO;
use Illuminate\Support\Facades\DB;
use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\BatchProductRepository;
use App\Repositories\PurchaseRefundRepository;
use App\Repositories\StorageProductRepository;

class CreatePurchaseRefundRepoAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(PurchaseRefundDTO $data): PurchaseRefund
    {
        return DB::transaction(function () use ($data) {

            $batch = BatchRepository::getModel()
                ->query()
                ->findOrFail($data->batchId);

            $refund = PurchaseRefundRepository::getModel()->create([
                'batch_id' => $batch->id,
            ]);

            foreach ($data->products as $product) {

                $batchProduct = BatchProductRepository::getModel()
                    ->query()
                    ->where('batch_id', $batch->id)
                    ->where('product_id', $product['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $availableQty =
                    $batchProduct->qty
                    - $batchProduct->sold_qty
                    - $batchProduct->refunded_qty;

                $refundQty = (int) $product['qty'];

                if ($refundQty > $availableQty) {
                    throw new \RuntimeException(
                        "Cannot refund {$refundQty} units. " .
                        "Only {$availableQty} units are available."
                    );
                }

                $refund->items()->create([
                    'product_id' => $product['product_id'],
                    'qty' => $refundQty,
                    'refund_price' => $batchProduct->purchase_price,
                ]);

                $batchProduct->increment(
                    'refunded_qty',
                    $refundQty
                );

                StorageProductRepository::getModel()
                    ->where('storage_id', $batch->storage_id)
                    ->where('product_id', $product['product_id'])
                    ->decrement('qty', $refundQty);
            }

            return $refund;
        });
    }
}
