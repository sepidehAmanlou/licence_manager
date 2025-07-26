<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('licenses')->insert([
            [
                'code' => 'LIC-001',
                'title' => 'مجوز کسب و کار اینترنتی',
                'description' => 'این مجوز برای راه‌اندازی فروشگاه‌های اینترنتی صادر می‌شود.',
                'issuer_organization_code' => 'ORG-123',
                'issue_duration_days' => 30,
                'valid_duration_days' => 365,
                'issue_fee' => 250000.00,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'LIC-002',
                'title' => 'مجوز خدمات فنی',
                'description' => 'این مجوز برای شرکت‌های خدمات فنی صادر می‌شود.',
                'issuer_organization_code' => 'ORG-456',
                'issue_duration_days' => 20,
                'valid_duration_days' => 180,
                'issue_fee' => 150000.00,
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
