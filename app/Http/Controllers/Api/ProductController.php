<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\SuccessResponseResource;
use App\Actions\Product\GetAvailableProductsAction;

class ProductController extends Controller
{
    public function available()
    {
        return SuccessResponseResource::make(['data' => GetAvailableProductsAction::run()]);
    }
}
