<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Batch\BatchProfitAction;
use App\Transformers\SuccessResponseResource;

class BatchController extends Controller
{
    public function profit()
    {
        return SuccessResponseResource::make(['data' => BatchProfitAction::run()]);
    }
}
