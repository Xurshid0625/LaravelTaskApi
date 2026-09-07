<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\SuccessResponseResource;
use App\Actions\Purchase\PurchaseProductsAction;

class PurchaseController extends Controller
{
    public function store()
    {
        return SuccessResponseResource::make(PurchaseProductsAction::run());
    }
}
