<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageProduct extends Model
{
    protected $fillable = [
        'storage_id',
        'product_id',
        'qty',
    ];

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
