<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use App\Models\Providers;
use Illuminate\Database\Seeder;

class PurchaseTestSeeder extends Seeder
{
    public function run(): void
    {
        $provider = Providers::create([
            'name' => 'Ahmad Tea',
        ]);

        $category = Category::create([
            'provider_id' => $provider->id,
            'name' => 'Black Tea',
            'parent_id' => null,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Ahmad Tea Earl Grey 500g',
            'price' => 25000,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Ahmad Tea English Breakfast 500g',
            'price' => 23000,
        ]);

        Storage::create([
            'name' => 'Main Storage',
        ]);
    }
}
