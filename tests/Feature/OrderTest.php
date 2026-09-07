<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use App\Models\Batch;
use App\Models\BatchProduct;
use App\Models\StorageProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_order_uses_oldest_available_batch(): void
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

        $oldBatch = Batch::create([
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
            'purchased_at' => '2026-09-01',
        ]);

        $newBatch = Batch::create([
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
            'purchased_at' => '2026-09-02',
        ]);

        BatchProduct::create([
            'batch_id' => $oldBatch->id,
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 0,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        BatchProduct::create([
            'batch_id' => $newBatch->id,
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 0,
            'refunded_qty' => 0,
            'purchase_price' => 20000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 200,
        ]);

        $response = $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'products' => [
                [
                    'id' => $product->id,
                    'qty' => 150,
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

        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
        ]);

        $this->assertDatabaseHas('order_products', [
            'product_id' => $product->id,
            'batch_id' => $oldBatch->id,
            'qty' => 100,
        ]);

        $this->assertDatabaseHas('order_products', [
            'product_id' => $product->id,
            'batch_id' => $newBatch->id,
            'qty' => 50,
        ]);

        $this->assertDatabaseHas('batch_products', [
            'batch_id' => $oldBatch->id,
            'product_id' => $product->id,
            'sold_qty' => 100,
        ]);

        $this->assertDatabaseHas('batch_products', [
            'batch_id' => $newBatch->id,
            'product_id' => $product->id,
            'sold_qty' => 50,
        ]);

        $this->assertDatabaseHas('storage_products', [
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 50,
        ]);
    }
}
