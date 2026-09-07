<?php

namespace App\DTOs;

readonly class PurchaseDTO
{
    public function __construct(
        public int $providerId,
        public int $storageId,
        public string $purchasedAt,
        public array $products,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            providerId: $data['provider_id'],
            storageId: $data['storage_id'],
            purchasedAt: $data['purchased_at'],
            products: $data['products'],
        );
    }
}
