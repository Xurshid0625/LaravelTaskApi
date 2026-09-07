<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Providers;
use App\Models\Storage;
use App\Models\StorageProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AvailableProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_products_are_returned(): void
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
            'name' => 'Ahmad Tea Earl Grey 500g',
            'price' => 25000,
        ]);

        StorageProduct::create([
            'storage_id' => $storage->id,
            'product_id' => $product->id,
            'qty' => 100,
        ]);

        $response = $this->getJson('/api/products/available');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => 'Ahmad Tea Earl Grey 500g',
            'category_name' => 'Black Tea',
            'price' => '25000.00',
            'qty' => 100,
        ]);
    }
}
