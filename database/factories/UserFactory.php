<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'national_code' => $this->faker->unique()->numerify('##########'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'father_name' => $this->faker->firstNameMale(),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'mobile' => $this->faker->unique()->numerify('09#########'),
            'postal_code' => $this->faker->numerify('##########'),
            'address' => $this->faker->address(),
        ];
    }
}
