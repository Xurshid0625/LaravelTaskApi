<?php

namespace App\Repositories\Actions\Batch;

use App\Models\Batch;
use App\Models\ClientRefundItems;
use Illuminate\Support\Collection;
use App\Models\PurchaseRefundItems;
use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\OrderProductRepository;

class GetRemainingStorageRepoAction
{
    use AsAction;

    public function handle(string $date): Collection
    {
        $batches = BatchRepository::getModel()->query()
            ->whereDate('purchased_at', '<=', $date)
            ->with('products')
            ->get();

        return $batches
            ->flatMap(function (Batch $batch) use ($date) {

                return $batch->products->map(function ($batchProduct) use ($batch, $date) {

                    $purchasedQty = $batchProduct->qty;

                    $purchaseRefundedQty = PurchaseRefundItems::query()
                        ->whereHas('refund', function ($query) use ($batch, $date) {
                            $query
                                ->where('batch_id', $batch->id)
                                ->whereDate('created_at', '<=', $date);
                        })
                        ->where('product_id', $batchProduct->product_id)
                        ->sum('qty');

                    $orderProducts = OrderProductRepository::getModel()->query()
                        ->where('batch_id', $batch->id)
                        ->where('product_id', $batchProduct->product_id)
                        ->whereHas('order', function ($query) use ($date) {
                            $query->whereDate('created_at', '<=', $date);
                        })
                        ->get();

                    $soldQty = $orderProducts->sum('qty');

                    $clientRefundedQty = ClientRefundItems::query()
                        ->whereIn(
                            'order_product_id',
                            $orderProducts->pluck('id')
                        )
                        ->whereHas('refund', function ($query) use ($date) {
                            $query->whereDate('created_at', '<=', $date);
                        })
                        ->sum('qty');

                    $remainingQty =
                        $purchasedQty
                        - $purchaseRefundedQty
                        - $soldQty
                        + $clientRefundedQty;

                    return [
                        'storage_id' => $batch->storage_id,
                        'product_id' => $batchProduct->product_id,
                        'qty' => $remainingQty,
                    ];
                });
            })
            ->groupBy(fn($item) => $item['storage_id'] . '-' . $item['product_id']
            )
            ->map(function ($items) {

                $first = $items->first();

                return [
                    'storage_id' => $first['storage_id'],
                    'product_id' => $first['product_id'],
                    'qty' => $items->sum('qty'),
                ];
            })
            ->values();
    }
}
