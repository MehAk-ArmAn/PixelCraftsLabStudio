<?php

namespace Database\Factories;

use App\Models\InteractiveExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InteractiveExperience>
 */
class InteractiveExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'page' => 'home',
            'section_key' => 'hero',
            'type' => 'logo_assemble',
            'enabled' => true,
            'accent_preset' => 'violet-orange',
            'intensity' => 1,
            'sort_order' => 10,
        ];
    }
}
