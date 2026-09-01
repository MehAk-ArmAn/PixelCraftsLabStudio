<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Testimonial> */
class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'quote' => fake()->paragraph(),
            'is_published' => true,
            'sort_order' => 10,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
