<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRefundItems extends Model
{
    protected $fillable = [
        'purchase_refund_id',
        'product_id',
        'qty',
        'refund_price',
    ];

    public function refund(): BelongsTo
    {
        return $this->belongsTo(PurchaseRefund::class, 'purchase_refund_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
