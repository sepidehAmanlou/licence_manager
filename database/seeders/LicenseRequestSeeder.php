<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenseRequestSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('license_requests')->insert([
    [
        'request_code' => 'REQ-1001',
        'user_id' => 1,
        'license_id' => 1,
        'business_postal_code' => '1234567890',
        'business_address' => 'تهران - خیابان آزادی - پلاک 25',
        'status' => 'approved',
        'requested_at' => Carbon::now()->subDays(5),
        'approved_at' => Carbon::now()->subDays(2),
        'expires_at' => Carbon::now()->addMonths(6),
        'admin_note' => 'درخواست تایید شد.',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'request_code' => 'REQ-1002',
        'user_id' => 2,
        'license_id' => 2,
        'business_postal_code' => '0987654321',
        'business_address' => 'اصفهان - خیابان امام - پلاک 10',
        'status' => 'pending',
        'requested_at' => Carbon::now(),
        'approved_at' => null,  
        'expires_at' => null,    
        'admin_note' => null,      
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]
]);

    }
}
