<?php

namespace App\Repositories\Actions\Client;

use Throwable;
use RuntimeException;
use App\Models\Order;
use App\DTOs\OrderDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\BatchProductRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\StorageProductRepository;

class CreateClientOrderRepoAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(OrderDTO $data): Order
    {
        return DB::transaction(function () use ($data) {

            $order = OrderRepository::getModel()->create([
                'client_id' => $data->clientId,
            ]);

            foreach ($data->products as $product) {

                $productId = $product['id'];
                $remainingQty = (int)$product['qty'];

                $batchProducts = BatchProductRepository::getModel()
                    ->query()
                    ->join('batches', 'batches.id', '=', 'batch_products.batch_id')
                    ->where('batch_products.product_id', $productId)
                    ->whereRaw(
                        'batch_products.qty > batch_products.sold_qty + batch_products.refunded_qty'
                    )
                    ->orderBy('batches.purchased_at')
                    ->orderBy('batch_products.id')
                    ->select('batch_products.*')
                    ->with('batch')
                    ->lockForUpdate()
                    ->get();

                foreach ($batchProducts as $batchProduct) {

                    if ($remainingQty <= 0) {
                        break;
                    }

                    $availableQty =
                        $batchProduct->qty
                        - $batchProduct->sold_qty
                        - $batchProduct->refunded_qty;

                    $sellQty = min(
                        $remainingQty,
                        $availableQty
                    );

                    OrderProductRepository::getModel()->create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'batch_id' => $batchProduct->batch_id,
                        'qty' => $sellQty,
                        'sale_price' => Product::query()
                            ->whereKey($productId)
                            ->value('price'),
                        'purchase_price' => $batchProduct->purchase_price,
                    ]);

                    $batchProduct->increment('sold_qty', $sellQty);

                    StorageProductRepository::getModel()
                        ->where('storage_id', $batchProduct->batch->storage_id)
                        ->where('product_id', $productId)
                        ->decrement('qty', $sellQty);

                    $remainingQty -= $sellQty;
                }

                if ($remainingQty > 0) {
                    throw new RuntimeException(
                        "Not enough stock for product {$productId}."
                    );
                }
            }

            return $order;
        });
    }
}
