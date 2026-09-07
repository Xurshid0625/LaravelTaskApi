<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchProduct extends Model
{
    protected $fillable = [
        'batch_id',
        'product_id',
        'qty',
        'sold_qty',
        'refunded_qty',
        'purchase_price',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
