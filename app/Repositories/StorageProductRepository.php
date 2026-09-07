<?php

namespace App\Repositories;

use App\Models\StorageProduct;
use Illuminate\Database\Eloquent\Collection;

class StorageProductRepository extends CoreRepository
{
    protected static string $model = StorageProduct::class;

    public static function getAvailableProducts(): Collection
    {
        return static::getModel()
            ->query()
            ->with(['product.category'])
            ->where('qty', '>', 0)
            ->get();
    }

    public static function getRemainingByDate(string $date)
    {
        return static::getModel()
            ->query()
            ->with(['product.category'])
            ->get();
    }
}
