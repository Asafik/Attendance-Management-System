<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Division;
use App\Models\Bank;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil division dari seeder (hanya IT dan Marketing)
        $itDivision = Division::where('name', 'IT')->first();
        $marketingDivision = Division::where('name', 'Marketing')->first();

        // Ambil bank dari seeder (sesuai BankSeeder)
        $bca = Bank::where('name', 'BCA')->first();
        $bri = Bank::where('name', 'BRI')->first();
        $bni = Bank::where('name', 'BNI')->first();
        $mandiri = Bank::where('name', 'Mandiri')->first();

        // Pastikan semua bank ditemukan
        if (!$bca || !$bri || !$bni || !$mandiri) {
            $this->command->error('❌ Bank tidak lengkap! Jalankan BankSeeder dulu.');
            return;
        }

        if (!$itDivision || !$marketingDivision) {
            $this->command->error('❌ Divisi tidak lengkap! Jalankan DivisionSeeder dulu.');
            return;
        }

        // Buat 8 data employees dengan variasi libur tetap
        $employees = [
            [
                'division_id'    => $itDivision->id,
                'bank_id'        => $bca->id,
                'name'           => 'Budi Santoso',
                'phone'          => '081234567890',
                'account_number' => '1234567890',
                'status'         => 'Aktif',
                'regular_off_day' => 'Minggu',
            ],
            [
                'division_id'    => $marketingDivision->id,
                'bank_id'        => $bri->id,
                'name'           => 'Siti Rahma',
                'phone'          => '081298765432',
                'account_number' => '9876543210',
                'status'         => 'Aktif',
                'regular_off_day' => 'Jumat',
            ],
            [
                'division_id'    => $itDivision->id,
                'bank_id'        => $mandiri->id,
                'name'           => 'Andi Pratama',
                'phone'          => '081355566677',
                'account_number' => '1122334455',
                'status'         => 'Nonaktif',
                'regular_off_day' => 'Sabtu',
            ],
            [
                'division_id'    => $marketingDivision->id,
                'bank_id'        => $bni->id,
                'name'           => 'Dewi Lestari',
                'phone'          => '081377788899',
                'account_number' => '2233445566',
                'status'         => 'Aktif',
                'regular_off_day' => 'Senin',
            ],
            [
                'division_id'    => $itDivision->id,
                'bank_id'        => $bca->id,
                'name'           => 'Rudi Hermawan',
                'phone'          => '081399900011',
                'account_number' => '3344556677',
                'status'         => 'Aktif',
                'regular_off_day' => 'Selasa',
            ],
            [
                'division_id'    => $marketingDivision->id,
                'bank_id'        => $bri->id,
                'name'           => 'Maya Sari',
                'phone'          => '081411122233',
                'account_number' => '4455667788',
                'status'         => 'Aktif',
                'regular_off_day' => 'Rabu',
            ],
            [
                'division_id'    => $itDivision->id,
                'bank_id'        => $mandiri->id,
                'name'           => 'Joko Widodo',
                'phone'          => '081422233344',
                'account_number' => '5566778899',
                'status'         => 'Aktif',
                'regular_off_day' => 'Kamis',
            ],
            [
                'division_id'    => $marketingDivision->id,
                'bank_id'        => $bni->id,
                'name'           => 'Ani Yudhoyono',
                'phone'          => '081433344455',
                'account_number' => '6677889900',
                'status'         => 'Aktif',
                'regular_off_day' => 'Tidak Libur',
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }
    }
}
