<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Organization;
use App\Models\Service;
// App\Models\Event::factory()->create()
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'organization_id' => Organization::factory()->create()->id,
            'service_id' => Service::factory()->create()->id,
            'name' =>  "Event-" . fake()->word(),
            'description' => fake()->text(),
            'live_url' => 'https://youtu.be/watch?v='.fake()->word('10'),
            'begin_at' => fake()->dateTimeThisMonth(),
            'duration_hours' => fake()->randomDigitNotNull(),
            'address' => fake()->address(),
            //'rrule'
            'is_need_check_out' => 1,
        ];
    }
}
