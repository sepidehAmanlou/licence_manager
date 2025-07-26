<?php

namespace Database\Factories;

use App\Models\LicenseRequest;
use App\Models\User;
use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseRequestFactory extends Factory
{
    protected $model = LicenseRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'license_id' => License::factory(),
            'business_postal_code' => $this->faker->numerify('##########'),
            'business_address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            // 'requested_at' => now(),
            'approved_at' => null,
            'rejected_at' => null,
            'expires_at' => now()->addMonths(6),
            'admin_note' => $this->faker->sentence(),
        ];
    }
}
