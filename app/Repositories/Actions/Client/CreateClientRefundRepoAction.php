<?php

namespace App\Repositories\Actions\Client;

use Throwable;
use App\Models\ClientRefund;
use App\DTOs\ClientRefundDTO;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\BatchProductRepository;
use App\Repositories\ClientRefundRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\StorageProductRepository;
use App\Repositories\ClientRefundItemRepository;

class CreateClientRefundRepoAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(ClientRefundDTO $data): ClientRefund
    {
        return DB::transaction(function () use ($data) {

            $order = OrderRepository::getModel()
                ->query()
                ->findOrFail($data->orderId);

            $refund = ClientRefundRepository::getModel()->create([
                'order_id' => $order->id,
            ]);

            foreach ($data->products as $product) {

                $orderProduct = OrderProductRepository::getModel()
                    ->query()
                    ->where('id', $product['order_product_id'])
                    ->where('order_id', $order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $refundQty = (int)$product['qty'];

                $alreadyRefundedQty = ClientRefundItemRepository::getModel()
                    ->query()
                    ->where('order_product_id', $orderProduct->id)
                    ->sum('qty');

                $availableQty =
                    $orderProduct->qty - $alreadyRefundedQty;

                if ($refundQty > $availableQty) {
                    throw new \RuntimeException(
                        "Cannot refund {$refundQty} units. " .
                        "Only {$availableQty} units are available."
                    );
                }

                ClientRefundItemRepository::getModel()->create([
                    'client_refund_id' => $refund->id,
                    'order_product_id' => $orderProduct->id,
                    'qty' => $refundQty,
                    'refund_price' => $orderProduct->sale_price,
                ]);

                $batchProduct = BatchProductRepository::getModel()
                    ->query()
                    ->where('batch_id', $orderProduct->batch_id)
                    ->where('product_id', $orderProduct->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $batchProduct->decrement(
                    'sold_qty',
                    $refundQty
                );

                $batchProduct->increment(
                    'refunded_qty',
                    $refundQty
                );

                StorageProductRepository::getModel()
                    ->where('storage_id', $orderProduct->batch->storage_id)
                    ->where('product_id', $orderProduct->product_id)
                    ->increment('qty', $refundQty);
            }

            return $refund;
        });
    }
}
