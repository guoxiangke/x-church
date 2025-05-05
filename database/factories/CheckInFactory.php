<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckIn>
 */
class CheckInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wxid' => 'test_user',
            'nickname' => 'test_user',
            'content' => 'checkIn',
            'check_in_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
