<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Batch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use App\Models\BatchProduct;
use App\Models\StorageProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemainingStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_remaining_storage_is_calculated_by_date(): void
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
            'name' => 'Ahmad Tea Earl Grey 500g',
            'price' => 25000,
        ]);

        $batch = Batch::create([
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
            'purchased_at' => '2026-09-01',
        ]);

        BatchProduct::create([
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 0,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 100,
        ]);

        $order = Order::create([
            'client_id' => $client->id,
        ]);

        $order->created_at = '2026-09-05';
        $order->save();

        OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'qty' => 30,
            'sale_price' => 25000,
            'purchase_price' => 18000,
        ]);

        $response = $this->getJson(
            '/api/storages/remaining?date=2026-09-07'
        );

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'status' => 'success',
            'data' => [
                [
                    'storage_id' => $storage->id,
                    'product_id' => $product->id,
                    'qty' => 70,
                ],
            ],
        ]);
    }
}
