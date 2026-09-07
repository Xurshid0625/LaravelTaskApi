<?php

namespace App\Repositories\Actions\Batch;

use App\Models\Batch;
use Illuminate\Support\Collection;
use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\OrderProductRepository;
use App\Repositories\ClientRefundItemRepository;

class GetBatchProfitRepoAction
{
    use AsAction;

    public function handle(): Collection
    {
        return BatchRepository::getModel()->query()
            ->with(['provider', 'products'])
            ->get()
            ->map(function (Batch $batch) {

                $revenue = 0;
                $cost = 0;

                $orderProducts = OrderProductRepository::getModel()->query()
                    ->where('batch_id', $batch->id)
                    ->get();

                foreach ($orderProducts as $orderProduct) {

                    $refundedQty = ClientRefundItemRepository::getModel()->query()
                        ->where('order_product_id', $orderProduct->id)
                        ->sum('qty');

                    $soldQty = $orderProduct->qty - $refundedQty;

                    $revenue += $soldQty * $orderProduct->sale_price;

                    $cost += $soldQty * $orderProduct->purchase_price;
                }

                return [
                    'batch_id' => $batch->id,
                    'provider_id' => $batch->provider_id,
                    'purchased_at' => $batch->purchased_at,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'profit' => $revenue - $cost,
                ];
            });
    }
}
