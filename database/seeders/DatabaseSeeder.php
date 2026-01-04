<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\BankSeeder;
use Database\Seeders\NewsEventsSeeder;
use Database\Seeders\ApiKeySeeder;
use Database\Seeders\HistoricalTradesSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {       
        
        $this->call([
            // RoleSeeder::class,
            // BankSeeder::class,
            // ApiKeySeeder::class,
            // NewsEventsSeeder::class,
            HistoricalTradesSeeder::class,
            // Add other seeders here
        ]);
    }
}
