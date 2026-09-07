<?php

namespace App\Actions\Batch;

use Illuminate\Support\Collection;
use App\Repositories\BatchRepository;
use Lorisleiva\Actions\Concerns\AsAction;

class BatchProfitAction
{
    use AsAction;

    public function handle(): Collection
    {
        return BatchRepository::getProfit();
    }
}
