<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(),
            'password' => Hash::make('1234'),
            'balance' => fake()->numerify(),
            'fcm' => "fcm fcm fcm",
            'own_code' => fake()->unique()->randomNumber(6),
            'user_id' => 1,
            'blocked' => rand(0, 1),
            'last_logged_id' => now(),
            'role' => 'user',
            'confirmed' => rand(0, 1),
        ];
    }
}
