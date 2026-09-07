<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRefund extends Model
{
    protected $fillable = [
        'batch_id',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRefundItems::class, 'purchase_refund_id');
    }
}
