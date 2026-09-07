<?php

namespace App\DTOs;

readonly class PurchaseRefundDTO
{
    public function __construct(
        public int   $batchId,
        public array $products,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            batchId: $data['batch_id'],
            products: $data['products'],
        );
    }
}
