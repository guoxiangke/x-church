<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
// Contact::factory()->count(50)->create()
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = fake()->dateTimeBetween('-30 days', '-1 days');
        return [
            'organization_id' => 2,
            'user_id' => User::factory()->create()->id, //教会成员，可以为空，即没有登记为系统用户
            'name_last'=> fake()->lastName(),
            'name_first'=> fake()->firstNameMale(),
            'name_en'=> fake()->name,
            'sex' => fake()->boolean(),
            'birthday' => now(),
            'telephone' => '+1' . fake()->tollFreePhoneNumber(),
            'email' => fake()->unique()->freeEmail(),
            'address' => fake()->address(),
            'date_join' => fake()->dateTimeBetween('-3 years', '-1 weeks'),
            // 'reference_id' => 
            'remark' => fake()->sentence(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
