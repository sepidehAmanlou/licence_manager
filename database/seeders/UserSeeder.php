<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'national_code' => '4271273104',
                'first_name' => 'علی',
                'last_name' => 'رضایی',
                'father_name' => 'حسن',
                'birth_date' => '1990-05-10',
                'gender' => 'male',
                'mobile' => '09120000001',
                'postal_code' => '1234567890',
                'address' => 'تهران - خیابان آزادی',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'national_code' => '4281560769',
                'first_name' => 'مریم',
                'last_name' => 'محمدی',
                'father_name' => 'کاظم',
                'birth_date' => '1992-08-20',
                'gender' => 'female',
                'mobile' => '09120000002',
                'postal_code' => '0987654321',
                'address' => 'اصفهان - خیابان امام',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
