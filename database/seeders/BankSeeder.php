<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Bank::insert([            
            [
                'payment_owner' => 'FDI',
                'appId' => 'D9AA542A-EAB0-4EED-9D65-BBC054F60DDC',
                'secret' => '07192788-21CF-4565-B8C8-EDA62FEEE063',
                'charges' => '2.5',
                'phone_number' => '0787373722',
                'balance' => 0,
            ],
        ]);
    }
}
