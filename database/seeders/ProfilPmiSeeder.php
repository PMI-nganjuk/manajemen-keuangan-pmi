<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationProfile;

class ProfilPmiSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationProfile::truncate();

        OrganizationProfile::create([
            'organization_name' => 'PALANG MERAH INDONESIA KABUPATEN NGANJUK',
            'address' => 'Jl. Mayjend Sungkono No. 10 Nganjuk',
            'chairperson' => 'Drs. Lishandoyo, M.Si',
            'headquarters_treasurer' => 'Luhur Budi Wahyono, SE, MM',
            'blood_donation_unit_treasurer' => 'Herin Purnawati, S.Pd',
            'financial_period_start' => '2025-01-01',
            'financial_period_end' => '2025-12-31',
            'fiscal_year' => 2025,
        ]);
    }
}
