<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'disable_game' => false,
            'saturday_open' => $this->faker->boolean,
            'sunday_open' => $this->faker->boolean,
            'auto_result' => $this->faker->boolean,
            'open_time' => Carbon::now(),
            'close_time' => Carbon::now()->addMinutes(5),
            'open_result_time' => $this->faker->time(),
            'close_result_time' => $this->faker->time(),
        ];
    }
}
