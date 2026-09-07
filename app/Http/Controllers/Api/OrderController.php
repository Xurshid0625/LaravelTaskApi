<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\SuccessResponseResource;
use App\Actions\Client\CreateClientOrderAction;

class OrderController extends Controller
{
    public function store()
    {
        return SuccessResponseResource::make(CreateClientOrderAction::run());
    }
}
