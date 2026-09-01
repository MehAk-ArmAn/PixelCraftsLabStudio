<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Project> */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->catchPhrase();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'category' => 'Web',
            'kind' => 'Website',
            'platform' => 'Web',
            'layout_size' => 'std',
            'short_description' => fake()->sentence(),
            'full_description' => fake()->paragraph(),
            'external_url' => 'https://example.com',
            'status' => Project::STATUS_PUBLISHED,
            'is_published' => true,
            'is_archived' => false,
            'sort_order' => 10,
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'status' => Project::STATUS_DRAFT,
        ]);
    }

    public function marketingCaseStudy(): static
    {
        return $this->state(fn () => [
            'category' => 'Marketing',
            'is_marketing_case_study' => true,
        ]);
    }
}
