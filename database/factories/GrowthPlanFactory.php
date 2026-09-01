<?php

namespace Database\Factories;

use App\Models\GrowthPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<GrowthPlan> */
class GrowthPlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'is_published' => true,
            'sort_order' => 10,
        ];
    }
}
