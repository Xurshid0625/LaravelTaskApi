<?php

namespace App\Actions\Product;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Repositories\StorageProductRepository;
use Illuminate\Support\Collection as SupportCollection;

class GetAvailableProductsAction
{
    use AsAction;

    public function handle(): SupportCollection
    {
        return StorageProductRepository::getAvailableProducts()
            ->map(function ($storageProduct) {
                return [
                    'id' => $storageProduct->product->id,
                    'name' => $storageProduct->product->name,
                    'category_name' => $storageProduct->product->category->name,
                    'price' => $storageProduct->product->price,
                    'qty' => $storageProduct->qty,
                ];
            });
    }
}
