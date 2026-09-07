<?php

namespace App\DTOs;

readonly class OrderDTO
{
    public function __construct(
        public int   $clientId,
        public array $products,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['client_id'],
            products: $data['products'],
        );
    }
}
