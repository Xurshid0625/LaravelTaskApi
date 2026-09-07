<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'name' => 'Makro Supermarket',
        ]);

        Client::create([
            'name' => 'Korzinka',
        ]);
    }
}
