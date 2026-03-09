<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyProfile;
use App\Models\User;
use Carbon\Carbon;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        if (CompanyProfile::count() > 0) {
            return;
        }

        $user = User::where('email', 'superadmin@alenamandiri.com')->first();

        if (!$user) {
            $user = User::first();
        }

        $companyData = [
            'name' => 'Alena Mandiri Group',
            'email' => 'info@alenamandiri.com',
            'phone' => '(021) 1234-5678',
            'address' => 'Jl. Raya Contoh No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'website' => 'https://www.alenamandiri.com',
            'logo' => null,
            'favicon' => null,
            'updated_by' => $user ? $user->id : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        CompanyProfile::create($companyData);
    }
}
