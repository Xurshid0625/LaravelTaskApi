<?php

namespace App\Repositories;

use App\Models\Order;
use App\DTOs\OrderDTO;
use App\Repositories\Actions\Client\CreateClientOrderRepoAction;

class OrderRepository extends CoreRepository
{
    protected static string $model = Order::class;

    public static function createOrder(OrderDTO $data): Order
    {
        return CreateClientOrderRepoAction::run($data);
    }
}
