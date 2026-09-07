<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_purchased_and_added_to_storage(): void
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
            'name' => 'Test Category',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 25000,
        ]);

        $response = $this->postJson('/api/purchases', [
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
            'purchased_at' => '2026-09-07',
            'products' => [
                [
                    'product_id' => $product->id,
                    'qty' => 100,
                    'purchase_price' => 18000,
                ],
            ],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'success' => true,
                'status' => 'success',
                'message' => 'Products purchased successfully.',
            ],
        ]);

        $this->assertDatabaseHas('batches', [
            'provider_id' => $provider->id,
            'storage_id' => $storage->id,
        ]);

        $this->assertDatabaseHas('batch_products', [
            'product_id' => $product->id,
            'qty' => 100,
            'sold_qty' => 0,
            'refunded_qty' => 0,
            'purchase_price' => 18000,
        ]);

        $this->assertDatabaseHas('storage_products', [
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 100,
        ]);
    }
}
