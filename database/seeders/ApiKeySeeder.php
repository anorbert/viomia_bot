<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\ApiKey::create([
            'key' => 'TEST_API_KEY_123',
            'label' => 'LocalTest'
        ]);

    }
}
