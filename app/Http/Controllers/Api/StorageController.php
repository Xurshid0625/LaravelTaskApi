<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\SuccessResponseResource;
use App\Actions\Storage\GetRemainingStorageAction;

class StorageController extends Controller
{
    public function remaining()
    {
        return SuccessResponseResource::make(['data' => GetRemainingStorageAction::run()]);
    }
}
