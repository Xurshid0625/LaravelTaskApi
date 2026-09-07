<?php

namespace App\DTOs;

readonly class ClientRefundDTO
{
    public function __construct(
        public int $orderId,
        public array $products,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            orderId: $data['order_id'],
            products: $data['products'],
        );
    }
}
