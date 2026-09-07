<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use App\Models\Batch;
use App\Models\BatchProduct;
use App\Models\OrderProduct;
use App\Models\StorageProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientRefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_refund_sold_product(): void
    {
        $provider = Providers::create([
            'name' => 'Test Provider',
        ]);

        $storage = Storage::create([
            'name' => 'Test Storage',
        ]);

        $client = Client::create([
            'name' => 'Test Client',
        ]);

        $category = Category::create([
            'provider_id' => $provider->id,
            'parent_id' => null,
            'name' => 'Black Tea',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 25000,
        ]);

        $batch = Batch::create([
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
            'purchased_at' => '2026-09-07',
        ]);

        BatchProduct::create([
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 100,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        $order = Order::create([
            'client_id' => $client->id,
        ]);

        $orderProduct = OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'qty' => 100,
            'sale_price' => 25000,
            'purchase_price' => 18000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 0,
        ]);

        $response = $this->postJson('/api/client-refunds', [
            'order_id' => $order->id,
            'products' => [
                [
                    'order_product_id' => $orderProduct->id,
                    'qty' => 20,
                ],
            ],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'success' => true,
                'status' => 'success',
            ],
        ]);

        $this->assertDatabaseHas('client_refunds', [
            'order_id' => $order->id,
        ]);

        $this->assertDatabaseHas('client_refund_items', [
            'order_product_id' => $orderProduct->id,
            'qty' => 20,
            'refund_price' => '25000.00',
        ]);

        $this->assertDatabaseHas('batch_products', [
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'sold_qty' => 80,
            'refunded_qty' => 20,
        ]);

        $this->assertDatabaseHas('storage_products', [
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 20,
        ]);
    }
}
