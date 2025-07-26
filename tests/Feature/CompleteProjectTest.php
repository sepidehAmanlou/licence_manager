<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\License;
use App\Models\LicenseRequest;
use App\Services\MellatBank;
use App\Services\SamanBank;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class CompleteProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * تست API دریافت درخواست‌های تایید شده
     */
    public function test_getApprovedRequests_success_and_errors()
    {
       $birthDate = '1360/01/01'; // فرمت Y/m/d

       Log::info('Birth Date: ' . $birthDate); // دیباگ

        // ساخت یک کاربر با تمام فیلدها، تبدیل تاریخ جلالی به میلادی با Jalalian:
        $user = User::factory()->create([
            'national_code' => '4271273104',
            'first_name' => 'Ali',
            'last_name' => 'Ahmadi',
            'father_name' => 'Reza',
            'birth_date' => $birthDate, 
            'gender' => 'male',
            'mobile' => '09123456789',
            'postal_code' => '1234567890',
            'address' => 'تهران، ایران',
        ]);

        // ساخت یک مجوز
        $license = License::factory()->create([
            'code' => 'LIC-001',
            'title' => 'مجوز نمونه',
            'description' => 'توضیحات مجوز نمونه',
            'issuer_organization_code' => 'ORG001',
            'issue_duration_days' => 30,
            'valid_duration_days' => 365,
            'issue_fee' => 100000,
            'status' => 'active',
        ]);

        // ساخت 3 درخواست مجوز تایید شده مرتبط با کاربر و مجوز
        LicenseRequest::factory()->count(3)->create([
            'user_id' => $user->id,
            'license_id' => $license->id,
            'business_postal_code' => '1234567890',
            'business_address' => 'آدرس شرکت نمونه',
            'status' => 'approved',
            // 'requested_at' => now(),
            'approved_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        // درخواست موفق با page_size مشخص
      $response = $this->postJson('/api/license_requests/approved_requests', [
    'national_code' => '4271273104',
    'page_size' => 2,
]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'request_id',
                        'request_code',
                        'license_code',
                        'license_title',
                        'requested_at',
                        'requested_at_jalali',
                        'business_postal_code',
                        'business_address',
                        'expires_at',
                        'expires_at_jalali',
                    ],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ]);

        // تست کاربری که وجود ندارد (کد ملی اشتباه)
      $response = $this->postJson('/api/license_requests/approved_requests', [
    'national_code' => '4281560769', 
     ]);

        $response->assertStatus(404);
        $response->assertJson(['status' => 404, 'message' => 'کاربر یافت نشد.']);


        // تست ولیدیشن اشتباه (کد ملی کوتاه)
      $response = $this->postJson('/api/license_requests/approved_requests', [
      'national_code' => '123',
    ]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['errors']);
    }

    /**
     * تست API دریافت فروشندگان کیبورد با کش و پاسخ
     */
 

public function test_getKeyboardSellersByCity_success_and_errors()
{
    
    Cache::forget('keyboard_sellers_tehran');

    // ماک کردن درخواست API
    Http::fake([
        'https://my.arian.co.ir/bpmsback/api/1.0/arian/arian/exercise/product-prices' => Http::response([
            [
                'product' => 'Keyboard X',
                'store' => 'Store 1',
                'price' => 90000,
                'shipping' => 10000,
                'city' => 'Tehran',
            ],
            [
                'product' => 'Keyboard Y',
                'store' => 'Store 2',
                'price' => 95000,
                'shipping' => 12000,
                'city' => 'Tehran',
            ],
        ], 200),
    ]);

    // تست موفق برای شهر تهران
   $response = $this->getJson('/api/keyboard_sellers?city=Tehran');
    $response->assertStatus(200);
    $response->assertJsonStructure([
    'status',
    'message',
    'data' => [
        'timestamp',
        'city',
        'average_price',
        'best_seller' => [
            'store',
            'product',
            'price',
            'shipping_cost',
            'city',
            'total_cost',
        ],
        'sellers' => [
            '*' => [
                'product',
                'store',
                'price',
                'city',
                'shipping',
                'total_cost',
            ],
        ],
    ],
]);


    // تست شهر نامعتبر
    $response = $this->getJson('/api/keyboard_sellers?city=InvalidCity');
    $response->assertStatus(400);
    $response->assertJsonStructure(['errors']);
}

    /**
     * تست کلاس‌های Bank برای توکن و تراکنش‌ها
     */
    public function test_bank_services_tokens_and_transactions()
    {
        $mellatBank = app(MellatBank::class);
        $samanBank = app(SamanBank::class);

        // تست توکن بانک ملت
        $tokenMellat = $mellatBank->getToken();
        $this->assertIsString($tokenMellat);
        $this->assertNotEmpty($tokenMellat);

        // تست ۳ تراکنش آخر بانک ملت
        $transactionsMellat = $mellatBank->getLastThreeTransactions();
        $this->assertIsArray($transactionsMellat);
        $this->assertCount(3, $transactionsMellat);

        // تست توکن بانک سامان
        $tokenSaman = $samanBank->getToken();
        $this->assertIsString($tokenSaman);
        $this->assertNotEmpty($tokenSaman);

        // تست ۳ تراکنش آخر بانک سامان
        $transactionsSaman = $samanBank->getLastThreeTransactions();
        $this->assertIsArray($transactionsSaman);
        $this->assertCount(3, $transactionsSaman);
    }
}
