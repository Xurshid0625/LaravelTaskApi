<?php

namespace App\Actions\Storage;

use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\RemainingStorageRequest;

class GetRemainingStorageAction
{
    use AsAction;

    public function __construct(protected RemainingStorageRequest $request)
    {
    }

    public function handle()
    {
        return BatchRepository::getRemainingByDate(
            $this->request->validated('date')
        );
    }
}
