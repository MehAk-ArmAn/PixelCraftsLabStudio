<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Service> */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'stage' => 'Engineer',
            'track' => Service::TRACK_BUILD,
            'body' => fake()->sentence(),
            'sort_order' => 10,
            'is_published' => true,
            'show_on_homepage' => true,
        ];
    }

    public function growth(): static
    {
        return $this->state(fn () => ['track' => Service::TRACK_GROWTH, 'stage' => 'Grow']);
    }
}
