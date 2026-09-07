<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\SuccessResponseResource;
use App\Actions\Purchase\PurchaseRefundAction;

class PurchaseRefundController extends Controller
{
    public function store()
    {
        return SuccessResponseResource::make(PurchaseRefundAction::run());
    }
}
