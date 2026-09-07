<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $fillable = [
        'provider_id',
        'storage_id',
        'purchased_at',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Providers::class);
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(BatchProduct::class);
    }
}
