<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Client\ClientRefundAction;
use App\Transformers\SuccessResponseResource;

class ClientRefundController extends Controller
{
    public function store()
    {
        return SuccessResponseResource::make(ClientRefundAction::run());
    }
}
