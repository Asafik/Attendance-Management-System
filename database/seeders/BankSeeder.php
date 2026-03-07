<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            'BCA',
            'BRI',
            'BNI',
            'Mandiri',
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['name' => $bank],
                ['name' => $bank]
            );
        }
    }
}
