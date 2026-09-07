<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientRefundItems extends Model
{
    protected $fillable = [
        'client_refund_id',
        'order_product_id',
        'qty',
        'refund_price',
    ];

    public function refund(): BelongsTo
    {
        return $this->belongsTo(ClientRefund::class, 'client_refund_id');
    }

    public function orderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }
}
