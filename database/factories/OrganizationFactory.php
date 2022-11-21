<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
// Organization::factory()->create()
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'system_name' => "Sys-" . fake()->firstNameMale(),
            'wechat_ai_title' => 'CheckIn助手',
            'name' => "CHU-en-" . fake()->firstNameMale(),
            'name_abbr' => 'Abbr-cn-' . fake()->firstNameMale(),
            'name_en' => 'CHU-En-' . fake()->firstNameMale(),
            'name_en_abbr' => 'Abbr-en-' . fake()->firstNameMale(),
            'telephone' => '+1' . fake()->tollFreePhoneNumber(),

            'email' => fake()->unique()->companyEmail(),
            'address' => fake()->url(),
            'website_url' => fake()->unique()->safeEmailDomain(),
            'logo_url' => fake()->imageUrl(360, 360, 'animals', true, 'cats', true, 'jpg'),
            'birthday' => now(),
            'introduce' => fake()->text(), 
            'contact_fields'=> 'date_baptized;is_married',
        ];
    }
}
