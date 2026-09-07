<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use App\Models\Batch;
use App\Models\BatchProduct;
use App\Models\StorageProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseRefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsold_purchased_products_can_be_refunded(): void
    {
        $provider = Providers::create([
            'name' => 'Test Provider',
        ]);

        $storage = Storage::create([
            'name' => 'Test Storage',
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
            'sold_qty' => 30,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 70,
        ]);

        $response = $this->postJson('/api/purchase-refunds', [
            'batch_id' => $batch->id,
            'products' => [
                [
                    'product_id' => $product->id,
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

        $this->assertDatabaseHas('purchase_refunds', [
            'batch_id' => $batch->id,
        ]);

        $this->assertDatabaseHas('purchase_refund_items', [
            'product_id' => $product->id,
            'qty' => 20,
            'refund_price' => '18000.00',
        ]);

        $this->assertDatabaseHas('batch_products', [
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 30,
            'refunded_qty' => 20,
        ]);

        $this->assertDatabaseHas('storage_products', [
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 50,
        ]);
    }

    public function test_purchase_refund_cannot_exceed_available_quantity(): void
    {
        $provider = Providers::create([
            'name' => 'Test Provider',
        ]);

        $storage = Storage::create([
            'name' => 'Test Storage',
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
            'sold_qty' => 30,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 70,
        ]);

        $response = $this->postJson('/api/purchase-refunds', [
            'batch_id' => $batch->id,
            'products' => [
                [
                    'product_id' => $product->id,
                    'qty' => 80,
                ],
            ],
        ]);

        $response->assertStatus(500);

        $this->assertDatabaseMissing('purchase_refunds', [
            'batch_id' => $batch->id,
        ]);

        $this->assertDatabaseHas('batch_products', [
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'sold_qty' => 30,
            'refunded_qty' => 0,
        ]);

        $this->assertDatabaseHas('storage_products', [
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 70,
        ]);
    }
}
