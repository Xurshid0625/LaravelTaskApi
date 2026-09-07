<?php

namespace App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResponseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $message = data_get($this->resource, 'message');
        $data = data_get($this->resource, 'data');

        return [
            'success' => true,
            'status' => 'success',
            'message' => $this->when(filled($message), fn() => $message),
            'data' => $this->when(filled($data), fn() => $data),
        ];
    }
}
