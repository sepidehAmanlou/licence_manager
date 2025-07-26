<?php

namespace Database\Factories;

use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition(): array
    {
        return [
            'code' => 'LIC-' . strtoupper($this->faker->bothify('??###')),
            'title' => 'مجوز ' . $this->faker->word(),
            'description' => $this->faker->sentence(8),
            'issuer_organization_code' => strtoupper($this->faker->bothify('ORG###')),
            'issue_duration_days' => $this->faker->numberBetween(10, 60),
            'valid_duration_days' => $this->faker->numberBetween(180, 730),
            'issue_fee' => $this->faker->numberBetween(100000, 500000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'archived']),
        ];
    }
}
